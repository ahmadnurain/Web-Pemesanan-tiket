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

        [$uuid, $sig] = array_pad(explode('|', $payload, 2), 2, null);
        if (!$uuid) {
            $this->error = 'Payload QR tidak valid.';
            Notification::make()->title('QR tidak valid')->danger()->send();
            return;
        }

        $tx = TicketTransaction::with('destination')
            ->where(function ($q) use ($uuid) {
                $q->where('uuid', $uuid)
                    ->orWhere('ticket_code', $uuid);
            })
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

        $isOwner = optional($tx->destination)->user_id === $user?->id;
        if (!$isOwner) {
            $this->error = 'Anda tidak berwenang memvalidasi tiket destinasi lain.';
            Notification::make()->title('Bukan destinasi Anda')->danger()->send();
            return;
        }
        // ========================================================================

        // Verifikasi tanda tangan QR (jika ada)
        if ($sig) {
            $expected = hash_hmac('sha256', $uuid, (string) $tx->qr_secret);
            if (!hash_equals($expected, $sig)) {
                $this->error = 'Tanda tangan QR tidak valid.';
                Notification::make()->title('Tanda tangan QR tidak valid')->danger()->send();
                return;
            }
        }

        // Cek Tanggal Kunjungan (Kadaluarsa)
        $today = now()->format('Y-m-d');
        $visitDate = $tx->visit_date ? \Carbon\Carbon::parse($tx->visit_date)->format('Y-m-d') : null;

        if ($visitDate && $visitDate !== $today) {
            // Tiket Kadaluarsa (Tanggal tidak sesuai)
            $tx->scan_count = (int) $tx->scan_count + 1;
            $tx->last_scanned_at = now();
            $tx->save();

            TicketScan::create([
                'ticket_transaction_id' => $tx->id,
                'user_id'               => $user?->id,
                'result'                => 'expired',
                'ip'                    => request()->ip(),
                'user_agent'            => (string) Str::limit(request()->userAgent() ?? '', 255),
            ]);

            $this->result = ['status' => 'expired', 'tx' => $tx];

            Notification::make()
                ->title('Tiket Kadaluarsa')
                ->body('Tanggal kunjungan tidak sesuai. Tiket untuk: ' . \Carbon\Carbon::parse($visitDate)->format('d M Y'))
                ->danger()
                ->send();

            return;
        }

        $already = $tx->ticket_status === 'used' || !empty($tx->used_at);

        // Catat selalu aktivitas scan
        $tx->scan_count      = (int) $tx->scan_count + 1;
        $tx->last_scanned_at = now();
        $tx->save();

        if (!$already) {
            $tx->ticket_status = 'used';
            $tx->used_at       = now();
            $tx->scanned_by    = $user?->id;
        }
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
