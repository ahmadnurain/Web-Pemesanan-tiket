<?php

namespace App\Http\Controllers;

use App\Mail\TicketLinkMail;
use App\Models\TicketTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class OrderLookupController extends Controller
{
    public function showForm()
    {
        return view('orders.lookup');
    }

    /** Normalisasi nomor HP ringan (contoh: 62 -> 0) */
    private function normalizePhone(?string $s): ?string
    {
        if (!$s) return null;
        $digits = preg_replace('/\D+/', '', $s);
        if (Str::startsWith($digits, '62')) {
            return '0' . substr($digits, 2);
        }
        return $digits;
    }

    public function search(Request $request)
    {
        // Validasi + pesan khusus
        $data = $request->validate(
            [
                'email'       => ['required', 'email'],
                'ticket_code' => ['nullable', 'string', 'required_without:phone_last4'],
                'phone_last4' => ['nullable', 'digits:4', 'required_without:ticket_code'],
            ],
            [
                'email.required'                 => 'Email wajib diisi.',
                'email.email'                    => 'Format email tidak valid.',
                'ticket_code.required_without'   => 'Isi Kode Tiket atau 4 digit terakhir No. HP.',
                'phone_last4.required_without'   => 'Isi 4 digit terakhir No. HP atau Kode Tiket.',
                'phone_last4.digits'             => '4 digit terakhir No. HP harus berisi 4 angka.',
            ]
        );

        $email  = trim($data['email']);
        $ticket = $data['ticket_code'] ?? null;
        $last4  = $data['phone_last4'] ?? null;

        // 1. Ambil transaksi milik email tsb (dibatasi 10 agar ringan, urutkan terbaru)
        $transactions = TicketTransaction::with('destination')
            ->where('email', $email)
            ->latest()
            ->limit(10)
            ->get();

        // Kalau email tidak punya transaksi sama sekali → error "Tidak Ditemukan"
        if ($transactions->isEmpty()) {
            return back()->withErrors(['lookup' => 'Email tidak ditemukan dalam sistem kami.'])->withInput();
        }

        // 2. Saring di PHP: ticket_code (case-insensitive) dan/atau 4 digit HP
        $matches = $transactions->filter(function ($tx) use ($ticket, $last4) {
            $okTicket = $ticket ? (strcasecmp($tx->ticket_code, $ticket) === 0) : true;
            $okPhone = true;
            if ($last4) {
                $digits = preg_replace('/\D+/', '', (string) $tx->phone_number);
                $okPhone = \Illuminate\Support\Str::endsWith($digits, $last4);
            }
            return $okTicket && $okPhone;
        });

        if ($matches->isEmpty()) {
            return back()->withErrors(['lookup' => 'Data verifikasi tidak cocok dengan Email tersebut.'])->withInput();
        }

        // 3. Simpan IDs ke session sebagai tanda "Verified"
        session([
            'lookup_verified_ids' => $matches->pluck('id')->toArray(),
            // Simpan email juga untuk UX
            'lookup_email' => $email
        ]);

        return redirect()->route('orders.lookup.result');
    }

    public function showResult()
    {
        $ids = session('lookup_verified_ids', []);

        if (empty($ids)) {
            return redirect()->route('orders.lookup.form')
                ->withErrors(['lookup' => 'Sesi kedaluwarsa, silakan cek pesanan kembali.']);
        }

        $transactions = TicketTransaction::with(['destination', 'items'])
            ->whereIn('id', $ids)
            ->latest()
            ->get();

        return view('orders.result', compact('transactions'));
    }

    public function showRescheduleForm(TicketTransaction $transaction)
    {
        $this->authorizeAccess($transaction);

        if (!$transaction->isPaymentSucceeded()) {
            return back()->with('error', 'Tiket belum lunas, tidak bisa reschedule.');
        }
        if ($transaction->isUsed()) {
            return back()->with('error', 'Tiket sudah dipakai, tidak bisa reschedule.');
        }

        return view('orders.reschedule', compact('transaction'));
    }

    public function processReschedule(Request $request, TicketTransaction $transaction)
    {
        $this->authorizeAccess($transaction);

        $request->validate([
            'visit_date' => ['required', 'date', 'after:today']
        ], [
            'visit_date.after' => 'Tanggal kunjungan baru harus di masa depan.'
        ]);

        $transaction->update([
            'visit_date' => $request->visit_date
        ]);

        // Opsional: Kirim email notifikasi perubahan jadwal
        // ...

        return redirect()->route('orders.lookup.result')
            ->with('status', 'Jadwal kunjungan berhasil diubah ke ' . \Carbon\Carbon::parse($request->visit_date)->isoFormat('D MMMM Y'));
    }

    private function authorizeAccess(TicketTransaction $transaction)
    {
        $ids = session('lookup_verified_ids', []);
        if (!in_array($transaction->id, $ids)) {
            abort(403, 'Akses ditolak.');
        }
    }

    // (opsional) endpoint kirim ulang 1 transaksi spesifik—kalau diperlukan
    public function sendDownloadLink(Request $request, TicketTransaction $transaction)
    {
        $this->authorizeAccess($transaction);

        $url = URL::signedRoute(
            'ticket.download',
            ['transaction' => $transaction]
        );

        Mail::to($transaction->email)->send(
            new TicketLinkMail($transaction, $transaction->destination, $url)
        );

        return back()->with('status', 'Tautan unduh dikirim ke email.');
    }
}
