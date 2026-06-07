<?php

use App\Http\Controllers\MidtransNotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\BookingController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Api\UlasanController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:sanctum', 'verified']);

Route::post('/midtrans/notifications', MidtransNotificationController::class)->name('api.midtrans.notifications');
Route::post('/midtrans/callback', MidtransNotificationController::class)->name('api.midtrans.callback');

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/resend-verification', [AuthController::class, 'resendVerification']);
    Route::post('/google', [AuthController::class, 'googleLogin']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return response()->make('
        <html>
        <head>
            <meta http-equiv="refresh" content="0;url=appkonkos://email-verified?status=success">
        </head>
        <body>
            <script>
                window.location = "appkonkos://email-verified?status=success";
                setTimeout(function() {
                    window.location = "appkonkos://email-verified?status=success";
                }, 500);
            </script>
            <p>Verifikasi berhasil! Membuka aplikasi...</p>
        </body>
        </html>
    ');
})->middleware(['signed'])->name('verification.verify');

Route::get('/all-properties', [PropertyController::class, 'index']);
Route::get('/ulasan', [UlasanController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    // ulasan routes
    Route::get('/ulasan/saya', [UlasanController::class, 'ulasanSaya']);                        
    Route::get('/ulasan/booking-belum-review', [UlasanController::class, 'bookingBelumReview']); 
    Route::get('/ulasan/cek', [UlasanController::class, 'cekBolehReview']);
    Route::post('/ulasan', [UlasanController::class, 'store']);
    Route::post('/ulasan/{id}/balas', [UlasanController::class, 'balas']); 
    // profile & booking routes
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
    Route::get('/bookings', [BookingController::class, 'index']);          
    Route::patch('/bookings/{id}/cancel', [BookingController::class, 'cancel']); 
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);
    // refund routes
    Route::post('/bookings/{id}/refund', function ($id, \Illuminate\Http\Request $request) {
        $request->validate(['alasan_refund' => 'required|string|max:500']);

        $booking = \App\Models\Booking::with('pembayaran')
            ->where('id', $id)
            ->where('pencari_kos_id', $request->user()->pencariKos->id)
            ->firstOrFail();

        if ($booking->status_booking !== 'lunas') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya booking yang sudah lunas yang bisa direfund'
            ], 422);
        }

        \App\Models\Refund::create([
            'booking_id'     => $booking->id,
            'pembayaran_id'  => $booking->pembayaran->id,
            'nominal_refund' => $booking->total_biaya,
            'alasan_refund'  => $request->alasan_refund,
            'status_refund'  => 'pending',
        ]);

        $booking->update(['status_booking' => 'refund']);

        return response()->json(['success' => true]);
    });
    // buat cek status booking & pembayaran setelah di bayar
    Route::get('/bookings/{id}/status', function ($id, \Illuminate\Http\Request $request) {
        $booking = \App\Models\Booking::with('pembayaran')
            ->where('id', $id)
            ->where('pencari_kos_id', $request->user()->pencariKos->id)
            ->first();

        if (!$booking) return response()->json(['error' => 'not found'], 404);

        return response()->json([
            'status_booking' => $booking->status_booking,
            'status_bayar'   => $booking->pembayaran?->status_bayar ?? '',
        ]);
    });
});

Route::get('/properties/kontrakan/{id}/detail', [PropertyController::class, 'detailKontrakan']);
Route::get('/properties/kosan/{id}/detail', [PropertyController::class, 'detailKosan']);
