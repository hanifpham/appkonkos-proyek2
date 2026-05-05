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
})->middleware('auth:sanctum');

Route::post('/midtrans/notifications', MidtransNotificationController::class)
    ->name('api.midtrans.notifications');

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::get('/all-properties', [PropertyController::class, 'index']);
Route::get('/properties/kosan/{id}/detail', [PropertyController::class, 'detailKosan']);
Route::get('/properties/kontrakan/{id}/detail', [PropertyController::class, 'detailKontrakan']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
    Route::get('/profile/status', [ProfileController::class, 'status']);
    Route::post('/bookings', [BookingController::class, 'store']);
});