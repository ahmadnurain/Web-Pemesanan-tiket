<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MidtransController;
use App\Http\Controllers\TicketScanController;

Route::post('/midtrans-notification', [MidtransController::class, 'handleNotification']);
