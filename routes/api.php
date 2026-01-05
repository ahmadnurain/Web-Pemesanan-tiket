<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MidtransController;
use App\Http\Controllers\Admin\TicketScanController;

Route::post('/midtrans-notification', [MidtransController::class, 'handleNotification']);

Route::middleware(['auth'])->prefix('admin')->group(function () {

});
