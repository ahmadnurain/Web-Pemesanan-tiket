<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Destinations;
use Illuminate\Http\Request;
use App\Models\TicketTransaction;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    // Menampilkan form pemesanan// Menampilkan form pemesanan
    public function showForm($id)
    {
        $destination = Destinations::with('photos')->findOrFail($id);
        return view('order-form', compact('destination'));
    }

    public function processOrder(Request $request)
    {
        // Format amount yang diterima
        $request->merge([
            'amount' => (float) str_replace(['IDR', ',', ' '], '', $request->amount)
        ]);

        // Validasi Data
        $request->validate([
            'destination_id' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'total_tickets' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        // Membuat transaksi dan menyimpan ke database
        $order_id = 'TKT' . strtoupper(uniqid('order_', true)) . '-' . time(); // Unik order_id

        $transaction = TicketTransaction::create([
            'destination_id' => $request->destination_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'total_tickets' => $request->total_tickets,
            'ticket_code' => 'TKT' . strtoupper(uniqid()),
            'amount' => $request->amount,
            'payment_status' => 'pending',
            'payment_type' => null, // Biarkan null, akan di-update nanti
            'ticket_status' => 'unused',
            'order_id' => $order_id, // Simpan order_id ke database
        ]);

        // Ambil data destination berdasarkan ID
        $destination = Destinations::findOrFail($request->destination_id);

        // Set konfigurasi Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false; // Ubah ke true di mode produksi
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Dapatkan Snap Token untuk transaksi
        $snapToken = Snap::getSnapToken([
            'transaction_details' => [
                'order_id' => $order_id, // Gunakan order_id yang konsisten
                'gross_amount' => $transaction->amount,
            ],
            'customer_details' => [
                'first_name' => $transaction->name,
                'email' => $transaction->email,
                'phone' => $transaction->phone_number,
            ],
        ]);

        // Update snap_token pada transaksi
        $transaction->update(['snap_token' => $snapToken]);

        // Ambil data customer untuk ditampilkan pada view
        $customer = $transaction;

        // Redirect ke halaman pembayaran Midtrans
        return view('payment', [
            'snapToken' => $snapToken,
            'destination' => $destination,
            'customer' => $customer // Pass customer data to view
        ]);
    }
    public function success(Request $request)
    {
        // Ambil snap_token dari query string
        $snap_token = $request->query('snap_token');

        // Cari transaksi berdasarkan snap_token
        $transaction = TicketTransaction::where('snap_token', $snap_token)->firstOrFail();

        // Ambil data destinasi berdasarkan ID transaksi
        $destination = Destinations::findOrFail($transaction->destination_id);
        $customer = $transaction;
        // Redirect ke view success
        return view('success', [
            'transaction' => $transaction,
            'destination' => $destination,
            'customer' => $customer // Pass customer data to view
        ]);
    }
}