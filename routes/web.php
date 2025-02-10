<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DestinationController;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/destinasi', [DestinationController::class, 'index']);
Route::get('/order-form/{id}', [OrderController::class, 'showForm'])->name('order.form');
Route::post('/order/process', [OrderController::class, 'processOrder'])->name('order.processOrder');
Route::get('/payment/success', [OrderController::class, 'success'])->name('payment.success');