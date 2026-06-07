<?php

use App\Http\Controllers\MidtransNotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\UlasanController;
use App\Models\User; // 1. DITAMBAHKAN: Mengimpor Model User untuk eksekusi manual

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

// --- 2. BAGIAN UTAMA YANG DIUBAH TOTAL ---
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    // Cari usernya secara manual berdasarkan ID yang dikirim dari URL email
    $user = User::findOrFail($id);

    // Validasi kecocokan hash/token keamanan bawaan Laravel
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return response()->json(['message' => 'Link verifikasi tidak sah atau sudah kedaluwarsa.'], 403);
    }

    // Jika lolos validasi dan status di DB masih NULL, ubah ke waktu sekarang (Verifikasi Sukses)
    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($user)); 
    }

    // Return HTML interaktif lengkap dengan tombol manual untuk menjebol blokir Chrome
    return response()->make('
        <html>
        <head>
            <title>Verifikasi Akun APPKONKOS</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body { font-family: sans-serif; text-align: center; padding-top: 50px; background-color: #f9f9f9; color: #333; }
                .btn { display: inline-block; padding: 12px 24px; background-color: #2196F3; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
                .loader { border: 4px solid #f3f3f3; border-top: 4px solid #2196F3; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 20px auto; }
                @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
            </style>
        </head>
        <body>
            <div class="loader"></div>
            <h2>Verifikasi Berhasil!</h2>
            <p>Akun Anda sudah aktif. Membuka aplikasi APPKONKOS...</p>
            
            <a href="appkonkos://email-verified" class="btn">Buka Aplikasi Manual</a>

            <script>
                // Otomatis coba lempar ke aplikasi Flutter
                window.location = "appkonkos://email-verified";
                setTimeout(function() {
                    window.location = "appkonkos://email-verified";
                }, 1000);
            </script>
        </body>
        </html>
    ');
})->middleware(['signed'])->name('verification.verify');
// --- AKHIR DARI BAGIAN YANG DIUBAH ---

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