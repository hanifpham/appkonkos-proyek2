<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\CariController;
use App\Http\Controllers\MidtransNotificationController;
use App\Http\Controllers\Pencari\PembayaranController;
use App\Livewire\Front\DetailProperti;
use App\Livewire\Front\PusatBantuan;
use App\Livewire\Mitra\Dashboard as MitraDashboard;
use App\Livewire\Mitra\KelolaKamarKos;
use App\Livewire\Mitra\Keuangan;
use App\Livewire\Mitra\Notifikasi as MitraNotifikasi;
use App\Livewire\Mitra\PengaturanProfil;
use App\Livewire\Mitra\PropertiSaya;
use App\Livewire\Mitra\RiwayatBooking;
use App\Livewire\Mitra\TambahKontrakan;
use App\Livewire\Mitra\TambahKosan;
use App\Livewire\Mitra\UlasanPenyewa;
use App\Livewire\Pencari\Checkout;
use App\Livewire\Pencari\FavoritSaya;
use App\Livewire\Pencari\ProfilSaya;
use App\Livewire\Pencari\RiwayatPesanan;
use App\Livewire\Pencari\UlasanSaya;
use App\Livewire\SuperAdmin\DashboardUtama as SuperAdminDashboard;
use App\Livewire\SuperAdmin\DetailModerasiProperti as SuperAdminDetailModerasiProperti;
use App\Livewire\SuperAdmin\ManajemenPencairan as SuperAdminManajemenPencairan;
use App\Livewire\SuperAdmin\ManajemenPengguna as SuperAdminManajemenPengguna;
use App\Livewire\SuperAdmin\ModerasiProperti as SuperAdminModerasiProperti;
use App\Livewire\SuperAdmin\PengajuanRefund as SuperAdminPengajuanRefund;
use App\Livewire\SuperAdmin\PengaturanProfil as SuperAdminPengaturanProfil;
use App\Livewire\SuperAdmin\TransaksiMidtrans as SuperAdminTransaksiMidtrans;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Http\Request;

Route::post('/midtrans/callback', MidtransNotificationController::class)->name('midtrans.callback');

// Google OAuth
Route::get('/auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirect'])
    ->name('auth.google');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'callback'])
    ->name('auth.google.callback');

Route::middleware('redirect.unverified')->group(function (): void {
    Route::get('/', BerandaController::class)->name('home');
    Route::get('/pusat-bantuan', PusatBantuan::class)->name('pusat-bantuan');
    Route::get('/cari', [CariController::class, 'index'])->name('cari');
    Route::get('/properti/{tipe}/{id}', DetailProperti::class)->name('properti.detail');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/pilih-role', function () {
        return redirect()->to('/#login');
    })->name('auth.pilih-role');

    Route::get('/portal-login/{role}', function (string $role): RedirectResponse {
        abort_unless(in_array($role, ['pencari', 'pemilik', 'superadmin'], true), 404);

        session(['login_portal' => $role]);

        return redirect()->route('login');
    })->name('auth.portal-login');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'role:pencari'])->group(function (): void {
    Route::get('/profil-saya', ProfilSaya::class)->name('pencari.profil');
    Route::get('/pembayaran/{booking}', [PembayaranController::class, 'show'])->name('pencari.pembayaran.show');
    Route::post('/pembayaran/{booking}/snap-token', [PembayaranController::class, 'snapToken'])->name('pencari.pembayaran.snap-token');
    Route::get('/favorit-saya', FavoritSaya::class)->name('pencari.favorit');
    Route::get('/riwayat-pesanan', RiwayatPesanan::class)->name('pencari.riwayat-pesanan');
    Route::get('/ulasan-saya', UlasanSaya::class)->name('pencari.ulasan-saya');
    Route::get('/checkout', Checkout::class)->name('pencari.checkout');
    Route::get('/e-ticket/{booking}', [PembayaranController::class, 'eTicket'])->name('pencari.e-ticket');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'role:pemilik'])->group(function (): void {
    Route::get('/mitra/dashboard', MitraDashboard::class)->name('mitra.dashboard');
    Route::get('/mitra/notifikasi', MitraNotifikasi::class)->name('mitra.notifikasi');
    Route::get('/mitra/properti', PropertiSaya::class)->name('mitra.properti');
    Route::get('/mitra/pesanan', RiwayatBooking::class)->name('mitra.pesanan');
    Route::get('/mitra/keuangan', Keuangan::class)->name('mitra.keuangan');
    Route::get('/mitra/ulasan', UlasanPenyewa::class)->name('mitra.ulasan');
    Route::get('/mitra/pengaturan-profil', PengaturanProfil::class)->name('mitra.pengaturan-profil');
    Route::middleware('profile.complete')->group(function () {
        Route::get('/mitra/properti/tambah-kosan', TambahKosan::class)->name('mitra.properti.tambah-kosan');
        Route::get('/mitra/properti/{kosan_id}/kelola-kamar', KelolaKamarKos::class)->name('mitra.properti.kelola-kamar');
        Route::get('/mitra/properti/tambah-kontrakan', TambahKontrakan::class)->name('mitra.properti.tambah-kontrakan');
    });
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'role:superadmin'])->group(function (): void {
    Route::get('/superadmin/dashboard', SuperAdminDashboard::class)->name('superadmin.dashboard');
    Route::get('/superadmin/pengguna', SuperAdminManajemenPengguna::class)->name('superadmin.pengguna');
    Route::get('/superadmin/properti', SuperAdminModerasiProperti::class)->name('superadmin.properti');
    Route::get('/superadmin/properti/{tipe}/{id}/detail', SuperAdminDetailModerasiProperti::class)->name('superadmin.moderasi.detail');
    Route::get('/superadmin/transaksi', SuperAdminTransaksiMidtrans::class)->name('superadmin.transaksi');
    Route::get('/superadmin/pencairan-dana', SuperAdminManajemenPencairan::class)->name('superadmin.pencairan');
    Route::get('/superadmin/refund', SuperAdminPengajuanRefund::class)->name('superadmin.refund');
    Route::get('/superadmin/pengaturan-profil', SuperAdminPengaturanProfil::class)->name('superadmin.pengaturan-profil');
});

// KODE BARU: BAJAK RUTE VERIFIKASI EMAIL LARAVEL
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::findOrFail($id);

    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return response()->json(['message' => 'Link verifikasi tidak sah atau kedaluwarsa.'], 403);
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($user)); 
    }

    return response()->make('
        <html>
        <head>
            <title>Verifikasi Berhasil - APPKONKOS</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; text-align: center; padding: 40px 20px; background-color: #f4f7f6; color: #333; }
                .box { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); max-width: 400px; margin: auto; }
                .icon { font-size: 50px; margin-bottom: 10px; }
                .btn { display: block; padding: 14px 24px; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 15px; transition: 0.3s; }
                .btn-mobile { background-color: #0056b3; }
                .btn-mobile:hover { background-color: #004494; }
                .btn-web { background-color: #28a745; }
                .btn-web:hover { background-color: #218838; }
                .text-muted { font-size: 14px; color: #6c757d; margin-top: 25px; }
            </style>
        </head>
        <body>
            <div class="box">
                <div class="icon">✅</div>
                <h2>Verifikasi Berhasil!</h2>
                <p>Akun <b>' . htmlspecialchars($user->name) . '</b> sudah aktif dan siap digunakan.</p>
                
                <p class="text-muted">Lanjutkan ke aplikasi yang sedang kamu gunakan:</p>
                
                <a href="appkonkos://email-verified" class="btn btn-mobile">📱 Buka di Aplikasi Mobile</a>
                
                <a href="/login" class="btn btn-web">💻 Lanjut Login di Website</a>
            </div>
        </body>
        </html>
    ');
})->middleware(['signed'])->name('verification.verify');