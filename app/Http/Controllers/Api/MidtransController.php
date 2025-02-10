<?php

namespace App\Http\Controllers\Api;

use Midtrans\Config;
use Illuminate\Http\Request;
use App\Models\TicketTransaction;
use Illuminate\Support\Facades\Log;
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
        $notification = new Notification();

        // Log data notifikasi
        Log::info('Midtrans Notification: ', (array)$notification);

        // Cari Transaksi berdasarkan ID Midtrans
        $transaction = TicketTransaction::where('order_id', $notification->order_id)->first();

        if (!$transaction) {
            Log::error('Transaction not found', ['order_id' => $notification->order_id]);
            return response()->json(['status' => 'not found'], 404);
        }

        // Perbarui Status Pembayaran
        $transactionStatus = $notification->transaction_status;
        $paymentType = $notification->payment_type;
        $fraudStatus = $notification->fraud_status;

        if ($transactionStatus == 'capture') {
            if ($paymentType == 'credit_card') {
                if ($fraudStatus == 'accept') {
                    $transaction->update(['payment_status' => 'succeeded']);
                } else {
                    $transaction->update(['payment_status' => 'failed']);
                }
            }
        } elseif ($transactionStatus == 'settlement') {
            $transaction->update(['payment_status' => 'succeeded']);
        } elseif ($transactionStatus == 'pending') {
            $transaction->update(['payment_status' => 'pending']);
        } elseif (
            $transactionStatus == 'deny' ||
            $transactionStatus == 'cancel' ||
            $transactionStatus == 'expire'
        ) {
            $transaction->update(['payment_status' => 'failed']);
        }

        // Perbarui tipe pembayaran
        $transaction->update(['payment_type' => $paymentType]);

        return response()->json(['status' => 'success']);
    }
}