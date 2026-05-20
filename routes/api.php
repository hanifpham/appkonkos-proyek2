<?php

use App\Http\Controllers\MidtransNotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\BookingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:sanctum', 'verified']);

Route::post('/midtrans/notifications', MidtransNotificationController::class)->name('api.midtrans.notifications');
Route::post('/midtrans/callback', MidtransNotificationController::class)->name('api.midtrans.callback');

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::get('/all-properties', [PropertyController::class, 'index']);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
     Route::get('/bookings', [BookingController::class, 'index']);          
    Route::patch('/bookings/{id}/cancel', [BookingController::class, 'cancel']); 
    Route::post('/bookings', [BookingController::class, 'store']);
});

Route::get('/properties/kontrakan/{id}/detail', [PropertyController::class, 'detailKontrakan']);
Route::get('/properties/kosan/{id}/detail', [PropertyController::class, 'detailKosan']);
