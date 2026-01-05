<?php

namespace App\Http\Controllers\Api;

use Midtrans\Config;
use Illuminate\Http\Request;
use App\Models\TicketTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketMail;
use App\Http\Controllers\Controller;
use Midtrans\Notification;

class MidtransController extends Controller
{

    public function handleNotification(Request $request)
    {
        // Setup Midtrans Configuration
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false; // Ubah ke true di mode produksi
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Ambil Notifikasi dari Midtrans
        try {
            $notification = new Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Invalid payload'], 400);
        }

        // Verifikasi Signature Key untuk keamanan (Mencegah manipulasi)
        // Rumus: SHA512(order_id + status_code + gross_amount + ServerKey)
        $validSignature = hash('sha512', $notification->order_id . $notification->status_code . $notification->gross_amount . Config::$serverKey);

        if ($notification->signature_key !== $validSignature) {
            Log::warning('Invalid Midtrans Signature Key', [
                'order_id' => $notification->order_id,
                'received' => $notification->signature_key,
                'expected' => $validSignature
            ]);
            return response()->json(['status' => 'forbidden', 'message' => 'Invalid Signature'], 403);
        }

        // Log data notifikasi
        Log::info('Midtrans Notification: ', (array)$notification);

        // Cari Transaksi berdasarkan ID Midtrans
        $transaction = TicketTransaction::where('order_id', $notification->order_id)->first();

        if (!$transaction) {
            Log::error('Transaction not found', ['order_id' => $notification->order_id]);
            return response()->json(['status' => 'not found'], 404);
        }

        // Perbarui Status Pembayaran (idempoten)
        $transactionStatus = $notification->transaction_status;
        $paymentType = $notification->payment_type;
        $fraudStatus = $notification->fraud_status;

        $previousStatus = $transaction->payment_status;
        $newStatus = $previousStatus; // default tidak berubah

        if ($transactionStatus === 'capture') {
            if ($paymentType === 'credit_card') {
                $newStatus = ($fraudStatus === 'accept') ? 'succeeded' : 'failed';
            }
        } elseif ($transactionStatus === 'settlement') {
            $newStatus = 'succeeded';
        } elseif ($transactionStatus === 'pending') {
            $newStatus = 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'], true)) {
            $newStatus = 'failed';
        }

        // Update sekali saja
        $transaction->update([
            'payment_status' => $newStatus,
            'payment_type' => $paymentType,
        ]);

        // Kirim e-ticket saat transisi ke succeeded (hindari duplikasi)
        if ($previousStatus !== 'succeeded' && $newStatus === 'succeeded') {
            try {
                // 1. Generate PDF
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('tickets.pdf', [
                    'transaction' => $transaction,
                    'destination' => $transaction->destination
                ]);
                $pdfContent = $pdf->output();

                // 2. Kirim Email (Sync / Langsung) agar tidak perlu Worker
                Mail::to($transaction->email)->send(
                    new TicketMail($transaction, $transaction->destination, $pdfContent)
                );
            } catch (\Throwable $e) {
                Log::error('Failed to send ticket mail', [
                    'order_id' => $transaction->order_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }
}
