<?php

use App\Http\Controllers\Pencari\PembayaranController;
use App\Livewire\Mitra\Dashboard as MitraDashboard;
use App\Livewire\Mitra\Keuangan;
use App\Livewire\Mitra\KelolaKamarKos;
use App\Livewire\Mitra\Notifikasi as MitraNotifikasi;
use App\Livewire\Mitra\PengaturanProfil;
use App\Livewire\Mitra\PropertiSaya;
use App\Livewire\Mitra\RiwayatBooking;
use App\Livewire\Mitra\TambahKontrakan;
use App\Livewire\Mitra\TambahKosan;
use App\Livewire\Mitra\UlasanPenyewa;
use App\Livewire\Pencari\Dashboard as PencariDashboard;

use App\Livewire\SuperAdmin\DetailModerasiProperti as SuperAdminDetailModerasiProperti;
use App\Livewire\SuperAdmin\DashboardUtama as SuperAdminDashboard;
use App\Livewire\SuperAdmin\ManajemenPencairan as SuperAdminManajemenPencairan;
use App\Livewire\SuperAdmin\ManajemenPengguna as SuperAdminManajemenPengguna;
use App\Livewire\SuperAdmin\ModerasiProperti as SuperAdminModerasiProperti;
use App\Livewire\SuperAdmin\PengajuanRefund as SuperAdminPengajuanRefund;
use App\Livewire\SuperAdmin\PengaturanProfil as SuperAdminPengaturanProfil;
use App\Livewire\SuperAdmin\TransaksiMidtrans as SuperAdminTransaksiMidtrans;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

Route::get('/', App\Http\Controllers\BerandaController::class)->name('home');
Route::get('/pusat-bantuan', App\Livewire\Front\PusatBantuan::class)->name('pusat-bantuan');
Route::get('/cari', [App\Http\Controllers\CariController::class, 'index'])->name('cari');
Route::get('/properti/{tipe}/{id}', App\Livewire\Front\DetailProperti::class)->name('properti.detail');

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

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'role:pencari'])->group(function (): void {
    Route::get('/dashboard', PencariDashboard::class)->name('dashboard');
    Route::get('/profil-saya', \App\Livewire\Pencari\ProfilSaya::class)->name('pencari.profil');
    Route::get('/pembayaran/{booking}', [PembayaranController::class, 'show'])->name('pencari.pembayaran.show');
    Route::post('/pembayaran/{booking}/snap-token', [PembayaranController::class, 'snapToken'])->name('pencari.pembayaran.snap-token');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'role:pemilik'])->group(function (): void {
    Route::get('/mitra/dashboard', MitraDashboard::class)->name('mitra.dashboard');
    Route::get('/mitra/notifikasi', MitraNotifikasi::class)->name('mitra.notifikasi');
    Route::get('/mitra/properti', PropertiSaya::class)->name('mitra.properti');
    Route::get('/mitra/pesanan', RiwayatBooking::class)->name('mitra.pesanan');
    Route::get('/mitra/keuangan', Keuangan::class)->name('mitra.keuangan');
    Route::get('/mitra/ulasan', UlasanPenyewa::class)->name('mitra.ulasan');
    Route::get('/mitra/pengaturan-profil', PengaturanProfil::class)->name('mitra.pengaturan-profil');
    Route::get('/mitra/properti/tambah-kosan', TambahKosan::class)->name('mitra.properti.tambah-kosan');
    Route::get('/mitra/properti/{kosan_id}/kelola-kamar', KelolaKamarKos::class)->name('mitra.properti.kelola-kamar');
    Route::get('/mitra/properti/tambah-kontrakan', TambahKontrakan::class)->name('mitra.properti.tambah-kontrakan');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'role:superadmin'])->group(function (): void {
    Route::get('/superadmin/dashboard', SuperAdminDashboard::class)->name('superadmin.dashboard');
    Route::get('/superadmin/pengguna', SuperAdminManajemenPengguna::class)->name('superadmin.pengguna');
    Route::get('/superadmin/properti', SuperAdminModerasiProperti::class)->name('superadmin.properti');
    Route::get('/superadmin/properti/{tipe}/{id}/detail', SuperAdminDetailModerasiProperti::class)->name('superadmin.moderasi.detail');
    Route::get('/superadmin/transaksi', SuperAdminTransaksiMidtrans::class)->name('superadmin.transaksi');
    Route::get('/superadmin/pencairan-dana', SuperAdminManajemenPencairan::class)->name('superadmin.pencairan');
    Route::get('/superadmin/refund', SuperAdminPengajuanRefund::class)->name('superadmin.refund');
    Route::get('/superadmin/pengaturan-profil', SuperAdminPengaturanProfil::class)->name('superadmin.pengaturan-profil');
});
