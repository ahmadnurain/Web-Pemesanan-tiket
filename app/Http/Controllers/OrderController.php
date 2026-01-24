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
        // 1) Validasi Dasar
        $rules = [
            'destination_id' => ['required', 'exists:destinations,id'],
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email'],
            'phone_number'   => ['required', 'string', 'max:30'],
            'visit_date'     => ['nullable', 'date', 'after_or_equal:today'],
        ];

        // Cek apakah pakai sistem Multi-Ticket (ticket_types array) atau Single (total_tickets)
        if ($request->has('ticket_types') && is_array($request->input('ticket_types'))) {
            $rules['ticket_types'] = ['required', 'array'];
            $rules['ticket_types.*'] = ['integer', 'min:0'];
        } else {
            $rules['total_tickets'] = ['required', 'integer', 'min:1'];
        }

        $validated = $request->validate($rules);
        $destination = Destinations::with('ticketTypes')->findOrFail($validated['destination_id']);

        // 2) Kalkulasi Harga & Item
        $amount = 0;
        $totalQty = 0;
        $itemsToCreate = [];

        // CASE A: Multi-Ticket (Mix)
        if ($request->has('ticket_types') && is_array($request->input('ticket_types'))) {
            foreach ($request->input('ticket_types') as $typeId => $qty) {
                $qty = (int)$qty;
                if ($qty > 0) {
                    $type = $destination->ticketTypes->find($typeId);
                    if ($type) {
                        $subtotal = $type->price * $qty;
                        $amount += $subtotal;
                        $totalQty += $qty;
                        $itemsToCreate[] = [
                            'destination_ticket_type_id' => $type->id,
                            'name' => $type->name,
                            'price_per_unit' => $type->price,
                            'quantity' => $qty,
                            'total_price' => $subtotal
                        ];
                    }
                }
            }
            if ($totalQty === 0) {
                return back()->withErrors(['total_tickets' => 'Mohon pilih minimal satu tiket.'])->withInput();
            }
        }
        // CASE B: Single Ticket (Legacy)
        else {
            $price = (int) $destination->ticket_price;
            if ($price <= 0) {
                return back()->withErrors(['amount' => 'Harga tiket normal tidak valid.'])->withInput();
            }
            $totalQty = (int) $validated['total_tickets'];
            $amount = (int) ($price * $totalQty);
            // Item default untuk legacy flow
            $itemsToCreate[] = [
                'destination_ticket_type_id' => null,
                'name' => 'Tiket Masuk Regular',
                'price_per_unit' => $price,
                'quantity' => $totalQty,
                'total_price' => $amount
            ];
        }

        // 4) Buat order_id yang rapi
        $orderId = 'TKT-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(8));

        // 5) Konfigurasi Midtrans
        MidtransConfig::$serverKey     = config('midtrans.server_key');
        MidtransConfig::$isProduction  = (bool) config('midtrans.is_production');
        // ... (Config check omitted for brevity, ensure handling) ...
        if (empty(MidtransConfig::$serverKey)) {
            // Fallback minimal error handling
            throw new \Exception('Midtrans Server Key belum diset.');
        }
        MidtransConfig::$isSanitized   = true;
        MidtransConfig::$is3ds         = true;

        // 6) Parameter transaksi untuk Snap
        // Siapkan item_details untuk Midtrans
        $midtransItems = [];
        foreach ($itemsToCreate as $item) {
            $midtransItems[] = [
                'id'       => $item['destination_ticket_type_id'] ? (string)$item['destination_ticket_type_id'] : 'REGULAR',
                'price'    => (int)$item['price_per_unit'],
                'quantity' => (int)$item['quantity'],
                'name'     => mb_strimwidth($item['name'], 0, 50),
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id'      => $orderId,
                'gross_amount'  => $amount,
            ],
            'customer_details' => [
                'first_name' => $validated['name'],
                'email'      => $validated['email'],
                'phone'      => $validated['phone_number'],
            ],
            'item_details' => $midtransItems
        ];

        // 7) Minta Snap Token
        try {
            $snapToken = MidtransSnap::getSnapToken($params);
        } catch (\Throwable $e) {
            report($e);
            return back()->withErrors(['payment' => 'Gagal menginisiasi pembayaran. Silakan coba lagi.'])->withInput();
        }

        // 8) Simpan transaksi + snap_token secara atomik
        $transaction = DB::transaction(function () use ($validated, $orderId, $amount, $totalQty, $snapToken, $itemsToCreate) {
            $tx = TicketTransaction::create([
                'destination_id' => $validated['destination_id'],
                'name'           => $validated['name'],
                'email'          => $validated['email'],
                'phone_number'   => $validated['phone_number'],
                'total_tickets'  => $totalQty,
                'ticket_code'    => 'TKT-' . Str::upper(Str::random(10)),
                'amount'         => $amount,
                'payment_status' => 'pending',
                'payment_type'   => null,
                'ticket_status'  => 'unused',
                'order_id'       => $orderId,
                'snap_token'     => $snapToken,
                'ticket_type'    => count($itemsToCreate) > 1 ? 'mixed' : ($validated['ticket_type'] ?? 'regular'),
                'visit_date'     => $validated['visit_date'] ?? null,
            ]);

            // Save Transaction Items
            foreach ($itemsToCreate as $item) {
                $tx->items()->create($item);
            }

            return $tx;
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
