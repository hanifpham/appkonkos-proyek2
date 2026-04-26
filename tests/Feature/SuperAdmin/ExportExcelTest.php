<?php

declare(strict_types=1);

namespace Tests\Feature\SuperAdmin;

use App\Livewire\SuperAdmin\ManajemenPengguna;
use App\Livewire\SuperAdmin\ModerasiProperti;
use App\Livewire\SuperAdmin\TransaksiMidtrans;
use App\Models\Booking;
use App\Models\Kontrakan;
use App\Models\Kosan;
use App\Models\Pembayaran;
use App\Models\PencariKos;
use App\Models\PemilikProperti;
use App\Models\TipeKamar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ExportExcelTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_download_users_excel_export(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
        ]);

        User::factory()->create([
            'role' => 'pencari',
            'name' => 'Pencari Export',
            'status' => 'aktif',
        ]);

        $this->actingAs($superadmin);

        Livewire::test(ManajemenPengguna::class)
            ->set('search', 'Pencari Export')
            ->call('eksporData')
            ->assertFileDownloaded();
    }

    public function test_superadmin_can_download_properties_excel_export(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
        ]);

        $pemilikUser = User::factory()->create([
            'role' => 'pemilik',
            'name' => 'Pemilik Export',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $pemilikUser->id,
            'nama_bank' => 'BRI',
            'nomor_rekening' => '999111',
        ]);

        $kosan = Kosan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kos Export',
            'alamat_lengkap' => 'Jl. Export Properti',
            'latitude' => -6.2,
            'longitude' => 106.8,
            'jenis_kos' => 'campur',
            'peraturan_kos' => 'Rapi',
            'status' => 'pending',
        ]);

        TipeKamar::create([
            'kosan_id' => $kosan->id,
            'nama_tipe' => 'Tipe A',
            'harga_per_bulan' => 850000,
            'fasilitas_tipe' => 'Kasur',
        ]);

        $this->actingAs($superadmin);

        Livewire::test(ModerasiProperti::class)
            ->set('search', 'Kos Export')
            ->set('filterStatus', 'pending')
            ->call('exportData')
            ->assertFileDownloaded();
    }

    public function test_superadmin_can_download_transactions_excel_export(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
        ]);

        $pemilikUser = User::factory()->create([
            'role' => 'pemilik',
            'name' => 'Pemilik Transaksi',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $pemilikUser->id,
            'nama_bank' => 'Mandiri',
            'nomor_rekening' => '777888',
            'status_verifikasi' => 'terverifikasi',
        ]);

        $kontrakan = Kontrakan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kontrakan Transaksi',
            'alamat_lengkap' => 'Jl. Pembayaran No. 2',
            'latitude' => -6.3,
            'longitude' => 106.9,
            'harga_sewa_tahun' => 15000000,
            'fasilitas' => 'Carport',
            'peraturan_kontrakan' => 'Tertib',
            'sisa_kamar' => 1,
            'status' => 'aktif',
        ]);

        $pencariUser = User::factory()->create([
            'role' => 'pencari',
            'name' => 'Penyewa Export',
        ]);

        $pencari = PencariKos::create([
            'user_id' => $pencariUser->id,
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1999-01-01',
            'pekerjaan' => 'Karyawan',
            'nama_instansi' => 'PT Export',
            'kota_asal' => 'Semarang',
        ]);

        $booking = Booking::create([
            'pencari_kos_id' => $pencari->id,
            'kontrakan_id' => $kontrakan->id,
            'tgl_mulai_sewa' => now()->toDateString(),
            'tgl_selesai_sewa' => now()->addMonths(12)->toDateString(),
            'total_biaya' => 15000000,
            'status_booking' => 'lunas',
        ]);

        Pembayaran::create([
            'booking_id' => $booking->id,
            'metode_bayar' => 'gopay',
            'waktu_bayar' => now(),
            'nominal_bayar' => 15000000,
            'status_bayar' => 'settlement',
            'midtrans_order_id' => 'ORDER-EXPORT-001',
        ]);

        $this->actingAs($superadmin);

        Livewire::test(TransaksiMidtrans::class)
            ->set('filterStatus', 'settlement')
            ->call('exportData')
            ->assertFileDownloaded();
    }
}
