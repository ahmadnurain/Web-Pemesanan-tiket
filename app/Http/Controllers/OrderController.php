<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Mail\TicketMail;
use Illuminate\Support\Str;
use App\Jobs\SendEticketJob;
use App\Models\Destinations;
use Illuminate\Http\Request;
use App\Models\TicketTransaction;
use Illuminate\Support\Facades\DB;
use Midtrans\Snap as MidtransSnap;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Midtrans\Config as MidtransConfig;


class OrderController extends Controller
{
    // Menampilkan form pemesanan// Menampilkan form pemesanan
    public function showForm(Destinations $destination)
    {
        $destination->load('photos');
        return view('order-form', compact('destination'));
    }

    public function processOrder(Request $request)
    {
        // 1) Validasi input dari user (tanpa menerima amount dari client)
        $validated = $request->validate([
            'destination_id' => ['required', 'exists:destinations,id'],
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email'],
            'phone_number'   => ['required', 'string', 'max:30'],
            'total_tickets'  => ['required', 'integer', 'min:1'],
            'visit_date'     => ['nullable', 'date', 'after_or_equal:today'], // opsional
            'ticket_type'    => ['nullable', 'in:dewasa,anak'],               // opsional
        ]);

        // 2) Ambil destinasi & pastikan harga valid (integer)
        $destination = Destinations::findOrFail($validated['destination_id']);
        $price = (int) $destination->ticket_price;
        if ($price <= 0) {
            return back()->withErrors(['amount' => 'Harga tiket tidak valid.'])->withInput();
        }

        $qty = (int) $validated['total_tickets'];

        // 3) Jika ke depan ada beda harga per tipe, terapkan faktor di sini
        // $factor = ($validated['ticket_type'] ?? 'dewasa') === 'anak' ? 0.8 : 1;
        // $amount = (int) round($price * $qty * $factor);
        $amount = (int) ($price * $qty);

        // 4) Buat order_id yang rapi (alnum + dash), unik, <= 50 char
        $orderId = 'TKT-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(8));

        // 5) Konfigurasi Midtrans
        MidtransConfig::$serverKey     = config('midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        MidtransConfig::$isProduction  = (bool) config('midtrans.is_production', false);
        MidtransConfig::$isSanitized   = true;
        MidtransConfig::$is3ds         = true;

        // 6) Parameter transaksi untuk Snap
        $params = [
            'transaction_details' => [
                'order_id'      => $orderId,
                'gross_amount'  => $amount,      // integer (wajib)
            ],
            'customer_details' => [
                'first_name' => $validated['name'],
                'email'      => $validated['email'],
                'phone'      => $validated['phone_number'],
            ],
            // item_details opsional tapi berguna (maks 50 chars utk name)
            'item_details' => [[
                'id'       => (string) $destination->id,
                'price'    => $price,
                'quantity' => $qty,
                'name'     => mb_strimwidth($destination->name, 0, 50),
            ]],
        ];

        // 7) Minta Snap Token, tangani error agar user dapat pesan jelas
        // 7) Minta Snap Token, tangani error agar user dapat pesan jelas
        try {
            $snapToken = MidtransSnap::getSnapToken($params);
        } catch (\Throwable $e) {
            report($e);
            return back()->withErrors(['payment' => 'Gagal menginisiasi pembayaran. Silakan coba lagi.'])->withInput();
        }

        // 8) Simpan transaksi + snap_token secara atomik
        $transaction = DB::transaction(function () use ($validated, $orderId, $amount, $snapToken) {
            return TicketTransaction::create([
                'destination_id' => $validated['destination_id'],
                'name'           => $validated['name'],
                'email'          => $validated['email'],
                'phone_number'   => $validated['phone_number'],
                'total_tickets'  => $validated['total_tickets'],
                'ticket_code'    => 'TKT-' . Str::upper(Str::random(10)),
                'amount'         => $amount,             // dihitung server-side
                'payment_status' => 'pending',
                'payment_type'   => null,
                'ticket_status'  => 'unused',
                'order_id'       => $orderId,
                'snap_token'     => $snapToken,
                // kolom baru (opsional) – pastikan sudah ada di migration & $fillable
                'ticket_type'    => $validated['ticket_type'] ?? 'regular',
                'visit_date'     => $validated['visit_date'] ?? null,
            ]);
        });

        // 9) Kirim ke view pembayaran
        return view('payment', [
            'snapToken'   => $snapToken,
            'destination' => $destination,
            'customer'    => $transaction, // view kamu sudah pakai $customer
        ]);
    }
    public function finalize(Request $request)
    {
        $snap_token = $request->input('snap_token');

        if (!$snap_token) {
            return redirect()->route('home');
        }

        $transaction = TicketTransaction::where('snap_token', $snap_token)->firstOrFail();

        // Simpan ID ke session
        session()->put('success_transaction_id', $transaction->id);

        // Kirim email e-ticket di sini agar hanya tereksekusi sekali saat redirect
        // (Idealnya via Webhook, tapi untuk flow ini kita taruh di sini)
        SendEticketJob::dispatch($transaction->id);

        return redirect()->route('payment.success');
    }

    public function success(Request $request)
    {
        // Ambil ID dari session
        $transactionId = session('success_transaction_id');

        if (!$transactionId) {
            // Fallback: jika user akses langsung tanpa session, redirect home
            return redirect()->route('home');
        }

        $transaction = TicketTransaction::findOrFail($transactionId);

        // Ambil data destinasi
        $destination = Destinations::findOrFail($transaction->destination_id);
        $customer = $transaction;

        return view('success', [
            'transaction' => $transaction,
            'destination' => $destination,
            'customer'    => $customer
        ]);
    }

    public function downloadTicket(TicketTransaction $transaction)
    {
        $pdf = PDF::loadView('tickets.pdf', ['transaction' => $transaction]);
        return $pdf->download('e-ticket-' . $transaction->ticket_code . '.pdf');
    }
    public function resendEticket(Request $request, TicketTransaction $transaction)
    {
        try {
            SendEticketJob::dispatch($transaction->id);
            return response()->json(['ok' => true]);
        } catch (\Throwable $e) {
            Log::error('Resend e-ticket failed', [
                'error' => $e->getMessage(),
                'tx_id' => $transaction->id,
            ]);
            return response()->json(['ok' => false, 'message' => 'Gagal mengirim ulang e-ticket'], 500);
        }
    }
}
