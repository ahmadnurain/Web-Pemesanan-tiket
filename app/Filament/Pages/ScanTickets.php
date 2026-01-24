<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\TicketScan;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\TicketTransaction;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class ScanTickets extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-qr-code';
    protected static ?string $navigationLabel = 'Scan & Validasi Tiket';
    protected static ?string $navigationGroup = 'Tiket';
    protected static ?string $title           = 'Scan & Validasi Tiket';
    protected static string $view             = 'filament.pages.app.filament.pages.scan-tickets';

    public ?array $result = null;
    public ?string $error = null;
    public string $manualCode = '';

    public function mount(): void
    {
        abort_unless(static::canAccess(), 403);
    }

    public function submitManual(): void
    {
        $code = trim($this->manualCode);
        if ($code === '') {
            $this->error = 'Masukkan kode terlebih dahulu.';
            Notification::make()->title('Masukkan kode terlebih dahulu')->warning()->send();
            return;
        }
        $this->handleScan($code);
        $this->manualCode = '';
    }

    #[On('qr-scanned')]
    public function handleScan(string $payload): void
    {
        $this->reset(['result', 'error']);

        [$codeCandidate, $sig] = array_pad(explode('|', $payload, 2), 2, null);
        if (!$codeCandidate) {
            $this->error = 'Payload QR tidak valid.';
            Notification::make()->title('QR tidak valid')->danger()->send();
            return;
        }

        // Cari berdasarkan ticket_code
        // (Kolom UUID sudah dihapus, jadi payload QR sekarang adalah ticket_code)
        $tx = TicketTransaction::with('destination')
            ->where('ticket_code', $codeCandidate)
            ->first();

        if (!$tx) {
            $this->error = 'Kode tiket tidak ditemukan.';
            Notification::make()->title('Tiket tidak ditemukan')->warning()->send();
            return;
        }

        $user = Auth::user();

        // ============== Akses hanya admin + harus pemilik destinasi ==============
        if ($user?->role !== 'admin') {
            $this->error = 'Halaman ini hanya untuk admin destinasi.';
            Notification::make()->title('Tidak berwenang')->danger()->send();
            return;
        }

        $isOwner = (int) optional($tx->destination)->user_id === (int) $user?->id;
        if (!$isOwner) {
            $this->error = 'Anda tidak berwenang memvalidasi tiket destinasi lain.';
            Notification::make()->title('Bukan destinasi Anda')->danger()->send();
            return;
        }
        // ========================================================================

        // Verifikasi tanda tangan QR (jika ada)
        if ($sig) {
            // Kita hash ticket_code dan secret
            $expected = hash_hmac('sha256', $tx->ticket_code, (string) $tx->qr_secret);
            if (!hash_equals($expected, $sig)) {
                $this->error = 'Tanda tangan QR tidak valid.';
                Notification::make()->title('Tanda tangan QR tidak valid')->danger()->send();
                return;
            }
        }

        // Cek Tanggal Kunjungan
        $today = now()->format('Y-m-d');
        $visitDate = $tx->visit_date ? \Carbon\Carbon::parse($tx->visit_date)->format('Y-m-d') : null;

        if ($visitDate && $visitDate !== $today) {
            $parsedVisit = \Carbon\Carbon::parse($visitDate);
            $isTooEarly = $parsedVisit->isFuture(); // Tiket untuk masa depan?

            // Cek status khusus
            $statusResult = $isTooEarly ? 'too_early' : 'expired';
            $titleMsg = $isTooEarly ? 'Tiket Belum Berlaku' : 'Tiket Kadaluarsa';

            // Tidak ada update scan_count/last_scanned_at lagi

            TicketScan::create([
                'ticket_transaction_id' => $tx->id,
                'user_id'               => $user?->id,
                'result'                => $statusResult,
                'ip'                    => request()->ip(),
                'user_agent'            => (string) Str::limit(request()->userAgent() ?? '', 255),
            ]);

            $this->result = ['status' => $statusResult, 'tx' => $tx];

            Notification::make()
                ->title($titleMsg)
                ->body('Tiket berlaku untuk tanggal: ' . $parsedVisit->translatedFormat('d F Y') . ($isTooEarly ? ' (Belum saatnya)' : ' (Sudah lewat)'))
                ->danger()
                ->send();

            return;
        }

        $already = $tx->ticket_status === 'used' || !empty($tx->used_at);

        if (!$already) {
            $tx->ticket_status = 'used';
            $tx->used_at       = now();
            $tx->scanned_by    = $user?->id;
        }
        // column scan_count & last_scanned_at deleted
        $tx->save();

        TicketScan::create([
            'ticket_transaction_id' => $tx->id,
            'user_id'               => $user?->id,
            'result'                => $already ? 'already_used' : 'valid',
            'ip'                    => request()->ip(),
            'user_agent'            => (string) Str::limit(request()->userAgent() ?? '', 255),
        ]);

        $this->result = ['status' => $already ? 'used' : 'ok', 'tx' => $tx];

        if ($already) {
            Notification::make()
                ->title('Tiket sudah pernah dipakai')
                ->body('Kode: ' . $tx->ticket_code)
                ->warning()
                ->send();
        } else {
            Notification::make()
                ->title('Tiket valid')
                ->body('Berhasil ditandai TERPAKAI. Kode: ' . $tx->ticket_code)
                ->success()
                ->send();
        }

        $this->dispatch(
            'swal',
            icon: $already ? 'warning' : 'success',
            title: $already ? 'Sudah dipakai' : 'Tiket valid',
            text: 'Kode: ' . $tx->ticket_code
        );
    }

    public static function canAccess(): bool
    {
        $u = Auth::user();
        // Hanya role 'admin' yang bisa akses halaman ini
        return $u && $u->role === 'admin';
    }
}
