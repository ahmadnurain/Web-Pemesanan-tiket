<?php

use App\Filament\Resources\CategoryResource;
use App\Http\Controllers\Admin\TicketScanController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderLookupController;
use App\Http\Middleware\CheckSuperAdmin;
use App\Mail\TicketMail;
use App\Models\TicketTransaction;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/destinasi', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinasi/{destination:slug}/detail', [DestinationController::class, 'show'])->name('destinations.show');
// Simpan filter/sort ke session (POST) lalu redirect agar URL bersih
Route::post('/destinasi/apply', [DestinationController::class, 'apply'])->name('destinations.apply');

// Hapus filter di session (POST) lalu redirect
Route::post('/destinasi/reset', [DestinationController::class, 'reset'])->name('destinations.reset');
Route::get('/order-form/{destination:slug}', [OrderController::class, 'showForm'])->name('order.form');
Route::post('/order/process', [OrderController::class, 'processOrder'])->name('order.processOrder');
Route::post('/payment/finalize', [OrderController::class, 'finalize'])->name('payment.finalize');
Route::get('/payment/success', [OrderController::class, 'success'])->name('payment.success');


Route::get('/ticket/download/{transaction:uuid}', [OrderController::class, 'downloadTicket'])
    ->name('ticket.download')
    ->middleware('signed'); // WAJIB
Route::post('/ticket/{transaction:uuid}/resend', [OrderController::class, 'resendEticket'])
    ->name('ticket.resend')
    ->middleware(['signed']);


Route::get('/pesanan/cek', [OrderLookupController::class, 'showForm'])
    ->name('orders.lookup.form');

Route::post('/pesanan/cek', [OrderLookupController::class, 'search'])
    ->name('orders.lookup.search');

// Kirim ulang link (rate limit)
Route::post('/pesanan/kirim-link/{transaction:uuid}', [OrderLookupController::class, 'sendDownloadLink'])
    ->name('orders.lookup.send_link')
    ->middleware(['throttle:5,1']);
// Route::get('/test-email', function () {
//     $transaction = \App\Models\TicketTransaction::latest()->first();
//     Mail::to($transaction->email)->send(new TicketMail($transaction));
//     return 'Email sent';
// });

// Route::middleware(['auth', 'super_admin'])->prefix('admin')->group(function () {
//     CategoryResource::routes(Filament::getDefaultPanel());
// });
Route::post('/tickets/scan', [TicketScanController::class, 'scan'])
    ->name('admin.tickets.scan');
