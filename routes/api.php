<?php

use App\Http\Controllers\MidtransNotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/midtrans/notifications', MidtransNotificationController::class)->name('api.midtrans.notifications');

Route::post('/midtrans/callback', [\App\Http\Controllers\Api\PaymentCallbackController::class, 'receive']);

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});