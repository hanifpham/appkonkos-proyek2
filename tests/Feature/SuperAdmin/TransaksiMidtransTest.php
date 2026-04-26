<?php

declare(strict_types=1);

namespace Tests\Feature\SuperAdmin;

use App\Livewire\SuperAdmin\TransaksiMidtrans;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Kontrakan;
use App\Models\Kosan;
use App\Models\Pembayaran;
use App\Models\PencairanDana;
use App\Models\PencariKos;
use App\Models\PemilikProperti;
use App\Models\TipeKamar;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

class TransaksiMidtransTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_view_and_filter_midtrans_transactions(): void
    {
        Carbon::setTestNow('2026-04-19 10:00:00');

        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'email' => 'superadmin-transaksi@example.com',
        ]);

        $pemilikUser = User::factory()->create([
            'role' => 'pemilik',
            'name' => 'Faldy Ardiansyah',
            'email' => 'faldy-transaksi@example.com',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $pemilikUser->id,
            'nama_bank' => 'Bank Test',
            'nomor_rekening' => '123456789',
            'status_verifikasi' => 'terverifikasi',
        ]);

        PencairanDana::create([
            'pemilik_properti_id' => $pemilik->id,
            'nominal' => 500000,
            'status' => 'pending',
        ]);

        $kosan = Kosan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kos Hidayat',
            'alamat_lengkap' => 'Jl. Hidayat No. 1',
            'latitude' => -6.4,
            'longitude' => 108.2,
            'jenis_kos' => 'putra',
            'peraturan_kos' => 'Tertib',
        ]);

        $tipeKamar = TipeKamar::create([
            'kosan_id' => $kosan->id,
            'nama_tipe' => 'Standar',
            'harga_per_bulan' => 600000,
            'fasilitas_tipe' => 'Kasur',
        ]);

        $kamar = Kamar::create([
            'tipe_kamar_id' => $tipeKamar->id,
            'nomor_kamar' => 'A1',
            'status_kamar' => 'tersedia',
        ]);

        $kontrakan = Kontrakan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kontrakan An-nur',
            'alamat_lengkap' => 'Jl. An-nur No. 2',
            'latitude' => -6.41,
            'longitude' => 108.21,
            'harga_sewa_tahun' => 6000000,
            'fasilitas' => 'Garasi',
            'peraturan_kontrakan' => 'Jaga kebersihan',
            'sisa_kamar' => 1,
        ]);

        $pencariOne = $this->createPencari('andi-midtrans@example.com', 'Andi Darmawan');
        $pencariTwo = $this->createPencari('budi-midtrans@example.com', 'Budi Santoso');

        $bookingKosan = Booking::create([
            'pencari_kos_id' => $pencariOne->id,
            'kamar_id' => $kamar->id,
            'kontrakan_id' => null,
            'tgl_mulai_sewa' => '2026-04-01',
            'tgl_selesai_sewa' => '2026-05-01',
            'total_biaya' => 600000,
            'status_booking' => 'lunas',
        ]);

        Pembayaran::create([
            'booking_id' => $bookingKosan->id,
            'metode_bayar' => 'gopay',
            'waktu_bayar' => now(),
            'nominal_bayar' => 600000,
            'status_bayar' => 'settlement',
            'url_struk_pdf' => null,
        ]);

        $bookingKontrakan = Booking::create([
            'pencari_kos_id' => $pencariTwo->id,
            'kamar_id' => null,
            'kontrakan_id' => $kontrakan->id,
            'tgl_mulai_sewa' => '2026-04-01',
            'tgl_selesai_sewa' => '2027-04-01',
            'total_biaya' => 6000000,
            'status_booking' => 'pending',
        ]);

        Pembayaran::create([
            'booking_id' => $bookingKontrakan->id,
            'metode_bayar' => 'bank_transfer',
            'waktu_bayar' => null,
            'nominal_bayar' => 6000000,
            'status_bayar' => 'pending',
            'url_struk_pdf' => null,
        ]);

        $this->actingAs($superadmin);

        $this->get(route('superadmin.transaksi'))
            ->assertOk()
            ->assertSee('Transaksi Midtrans')
            ->assertSee('Rp 600.000')
            ->assertSee('1 Pengajuan');

        Livewire::test(TransaksiMidtrans::class)
            ->assertSee('Andi Darmawan')
            ->assertSee('Budi Santoso')
            ->assertSee('Kos Hidayat')
            ->assertSee('Kontrakan An-nur')
            ->set('filterStatus', 'settlement')
            ->assertSee('Andi Darmawan')
            ->assertDontSee('Budi Santoso')
            ->set('filterStatus', '')
            ->set('filterMetode', 'bank_transfer')
            ->assertDontSee('Andi Darmawan')
            ->assertSee('Budi Santoso')
            ->set('filterMetode', '')
            ->assertSee('Andi Darmawan')
            ->assertSee('Budi Santoso')
            ->call('exportReport')
            ->assertFileDownloaded('transaksi-midtrans-20260419-100000.csv');

        Carbon::setTestNow();
    }

    public function test_superadmin_can_sync_pending_midtrans_transaction_status(): void
    {
        config([
            'midtrans.server_key' => 'test-server-key',
            'midtrans.is_production' => false,
        ]);

        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'email' => 'superadmin-sync-midtrans@example.com',
        ]);

        $pemilikUser = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'pemilik-sync-midtrans@example.com',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $pemilikUser->id,
            'nama_bank' => 'Bank Test',
            'nomor_rekening' => '123456789',
            'status_verifikasi' => 'terverifikasi',
        ]);

        $kosan = Kosan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kos Sinkron',
            'alamat_lengkap' => 'Jl. Sinkron No. 1',
            'latitude' => -6.4,
            'longitude' => 108.2,
            'jenis_kos' => 'putra',
            'peraturan_kos' => 'Tertib',
        ]);

        $tipeKamar = TipeKamar::create([
            'kosan_id' => $kosan->id,
            'nama_tipe' => 'Standar',
            'harga_per_bulan' => 700000,
            'fasilitas_tipe' => 'Kasur',
        ]);

        $kamar = Kamar::create([
            'tipe_kamar_id' => $tipeKamar->id,
            'nomor_kamar' => 'B1',
            'status_kamar' => 'tersedia',
        ]);

        $pencari = $this->createPencari('sync-midtrans@example.com', 'Sinkron Midtrans');

        $booking = Booking::create([
            'pencari_kos_id' => $pencari->id,
            'kamar_id' => $kamar->id,
            'kontrakan_id' => null,
            'tgl_mulai_sewa' => '2026-05-01',
            'tgl_selesai_sewa' => '2026-06-01',
            'total_biaya' => 700000,
            'status_booking' => 'pending',
        ]);

        $payment = Pembayaran::create([
            'booking_id' => $booking->id,
            'metode_bayar' => 'qris',
            'waktu_bayar' => null,
            'nominal_bayar' => 700000,
            'status_bayar' => 'pending',
            'url_struk_pdf' => null,
            'midtrans_order_id' => 'ORDER-SYNC-001',
        ]);

        Mockery::mock('alias:Midtrans\Transaction')
            ->shouldReceive('status')
            ->once()
            ->with('ORDER-SYNC-001')
            ->andReturn((object) [
                'transaction_status' => 'settlement',
                'transaction_id' => 'trx-sync-001',
                'payment_type' => 'qris',
                'fraud_status' => 'accept',
                'settlement_time' => '2026-05-01 10:30:00',
            ]);

        $this->actingAs($superadmin);

        Livewire::test(TransaksiMidtrans::class)
            ->call('syncMidtrans', 'ORDER-SYNC-001')
            ->assertDispatched('appkonkos-toast');

        $this->assertDatabaseHas('pembayaran', [
            'id' => $payment->id,
            'status_bayar' => 'lunas',
            'status_midtrans' => 'settlement',
            'midtrans_transaction_id' => 'trx-sync-001',
            'metode_bayar' => 'qris',
        ]);
    }

    private function createPencari(string $email, string $name): PencariKos
    {
        $user = User::factory()->create([
            'role' => 'pencari',
            'email' => $email,
            'name' => $name,
        ]);

        return PencariKos::create([
            'user_id' => $user->id,
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
            'pekerjaan' => 'Mahasiswa',
            'nama_instansi' => 'Polindra',
            'kota_asal' => 'Indramayu',
        ]);
    }
}
