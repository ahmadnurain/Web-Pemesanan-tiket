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

        // Ambil transaksi milik email tsb (dibatasi 10 agar ringan)
        $transactions = \App\Models\TicketTransaction::with('destination')
            ->where('email', $email)
            ->latest()
            ->limit(10)
            ->get();

        // Kalau email tidak punya transaksi sama sekali → error
        if ($transactions->isEmpty()) {
            return back()
                ->withErrors(['lookup' => 'Data tidak cocok atau tidak ditemukan. Periksa kembali email dan data verifikasi Anda.'])
                ->withInput();
        }

        // Saring di PHP: ticket_code (case-insensitive) dan/atau 4 digit HP
        $filtered = $transactions->filter(function ($tx) use ($ticket, $last4) {
            $okTicket = $ticket ? (strcasecmp($tx->ticket_code, $ticket) === 0) : true;

            $okPhone = true;
            if ($last4) {
                $digits = preg_replace('/\D+/', '', (string) $tx->phone_number);
                $okPhone = \Illuminate\Support\Str::endsWith($digits, $last4);
            }

            return $okTicket && $okPhone;
        });

        // Tidak ada yang cocok → error
        if ($filtered->isEmpty()) {
            return back()
                ->withErrors(['lookup' => 'Data tidak cocok atau tidak ditemukan. Pastikan Kode Tiket atau 4 digit terakhir No. HP benar.'])
                ->withInput();
        }

        // Kirim email berisi tautan unduh (signed + expired) DENGAN QUEUE
        foreach ($filtered as $tx) {
            $link = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                'ticket.download',
                now()->addMinutes(30),
                ['transaction' => $tx->uuid]
            );

            \Illuminate\Support\Facades\Mail::to($tx->email)->send(
                new \App\Mail\TicketLinkMail($tx, $tx->destination, $link)
            );
        }

        // Sukses
        return back()->with('status', 'Jika data cocok, kami telah mengirim tautan unduh ke email Anda.');
    }


    // (opsional) endpoint kirim ulang 1 transaksi spesifik—kalau diperlukan
    public function sendDownloadLink(Request $request, TicketTransaction $transaction)
    {
        $url = URL::temporarySignedRoute(
            'ticket.download',
            now()->addMinutes(30),
            ['transaction' => $transaction->uuid]
        );

        Mail::to($transaction->email)->send(
            new TicketLinkMail($transaction, $transaction->destination, $url)
        );

        return back()->with('status', 'Tautan unduh dikirim ke email.');
    }
}
