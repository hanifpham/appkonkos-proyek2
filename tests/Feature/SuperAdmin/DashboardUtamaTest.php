<?php

declare(strict_types=1);

namespace Tests\Feature\SuperAdmin;

use App\Livewire\SuperAdmin\DashboardUtama;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Kosan;
use App\Models\Pembayaran;
use App\Models\PencairanDana;
use App\Models\PencariKos;
use App\Models\PemilikProperti;
use App\Models\Setting;
use App\Models\TipeKamar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

class DashboardUtamaTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_utama_uses_dynamic_platform_commission_for_total_revenue(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'email' => 'superadmin-dashboard@example.com',
        ]);

        Setting::putValue(Setting::KEY_PLATFORM_COMMISSION, 10);

        [$booking] = $this->createBookingGraph('dashboard-sukses@example.com', 'ORDER-DASH-001');

        Pembayaran::create([
            'booking_id' => $booking->id,
            'metode_bayar' => 'qris',
            'waktu_bayar' => now(),
            'nominal_bayar' => 1000000,
            'status_bayar' => 'lunas',
            'midtrans_order_id' => 'ORDER-DASH-001',
            'status_midtrans' => 'settlement',
        ]);

        PencairanDana::create([
            'pemilik_properti_id' => $booking->kamar?->tipeKamar?->kosan?->pemilikProperti?->id,
            'nominal' => 300000,
            'status' => 'pending',
        ]);

        $this->actingAs($superadmin);

        Livewire::test(DashboardUtama::class)
            ->assertSee('Rp 100.000')
            ->assertSee('Komisi 10% dari settlement Rp 1.000.000');
    }

    public function test_dashboard_utama_can_sync_latest_pending_midtrans_transactions(): void
    {
        config([
            'midtrans.server_key' => 'test-server-key',
            'midtrans.is_production' => false,
        ]);

        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'email' => 'superadmin-dashboard-sync@example.com',
        ]);

        [$bookingA] = $this->createBookingGraph('dashboard-sync-a@example.com', 'ORDER-DASH-SYNC-001');
        [$bookingB] = $this->createBookingGraph('dashboard-sync-b@example.com', 'ORDER-DASH-SYNC-002');

        $paymentA = Pembayaran::create([
            'booking_id' => $bookingA->id,
            'metode_bayar' => 'qris',
            'nominal_bayar' => 800000,
            'status_bayar' => 'pending',
            'midtrans_order_id' => 'ORDER-DASH-SYNC-001',
            'status_midtrans' => 'pending',
        ]);

        $paymentB = Pembayaran::create([
            'booking_id' => $bookingB->id,
            'metode_bayar' => 'gopay',
            'nominal_bayar' => 900000,
            'status_bayar' => 'pending',
            'midtrans_order_id' => 'ORDER-DASH-SYNC-002',
            'status_midtrans' => 'pending',
        ]);

        $transaction = Mockery::mock('alias:Midtrans\Transaction');

        $transaction->shouldReceive('status')
            ->once()
            ->with('ORDER-DASH-SYNC-001')
            ->andReturn((object) [
                'order_id' => 'ORDER-DASH-SYNC-001',
                'transaction_status' => 'settlement',
                'payment_type' => 'qris',
                'transaction_id' => 'trx-sync-001',
                'fraud_status' => 'accept',
            ]);

        $transaction->shouldReceive('status')
            ->once()
            ->with('ORDER-DASH-SYNC-002')
            ->andReturn((object) [
                'order_id' => 'ORDER-DASH-SYNC-002',
                'transaction_status' => 'pending',
                'payment_type' => 'gopay',
                'transaction_id' => 'trx-sync-002',
                'fraud_status' => 'accept',
            ]);

        $this->actingAs($superadmin);

        Livewire::test(DashboardUtama::class)
            ->call('syncMassalPending')
            ->assertDispatched('appkonkos-toast');

        $this->assertDatabaseHas('pembayaran', [
            'id' => $paymentA->id,
            'status_bayar' => 'lunas',
            'status_midtrans' => 'settlement',
        ]);

        $this->assertDatabaseHas('pembayaran', [
            'id' => $paymentB->id,
            'status_bayar' => 'pending',
            'status_midtrans' => 'pending',
        ]);
    }

    /**
     * @return array{0: Booking, 1: PemilikProperti}
     */
    private function createBookingGraph(string $pencariEmail, string $orderId): array
    {
        $suffix = strtolower(substr(md5($pencariEmail.$orderId), 0, 6));

        $pemilikUser = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'pemilik-'.$suffix.'@example.com',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $pemilikUser->id,
            'nama_bank' => 'BCA',
            'nomor_rekening' => '1234567890',
            'status_verifikasi' => 'terverifikasi',
        ]);

        $kosan = Kosan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kos Dashboard '.$suffix,
            'alamat_lengkap' => 'Jl. Dashboard No. 1',
            'latitude' => -6.4,
            'longitude' => 108.2,
            'jenis_kos' => 'putra',
            'peraturan_kos' => 'Tertib',
        ]);

        $tipeKamar = TipeKamar::create([
            'kosan_id' => $kosan->id,
            'nama_tipe' => 'Reguler '.$suffix,
            'harga_per_bulan' => 1000000,
            'fasilitas_tipe' => 'Kasur',
        ]);

        $kamar = Kamar::create([
            'tipe_kamar_id' => $tipeKamar->id,
            'nomor_kamar' => 'A-'.$suffix,
            'status_kamar' => 'tersedia',
        ]);

        $pencariUser = User::factory()->create([
            'role' => 'pencari',
            'email' => $pencariEmail,
        ]);

        $pencari = PencariKos::create([
            'user_id' => $pencariUser->id,
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2001-01-01',
            'pekerjaan' => 'Karyawan',
            'nama_instansi' => 'PT Appkonkos',
            'kota_asal' => 'Indramayu',
        ]);

        $booking = Booking::create([
            'pencari_kos_id' => $pencari->id,
            'kamar_id' => $kamar->id,
            'kontrakan_id' => null,
            'tgl_mulai_sewa' => '2026-06-01',
            'tgl_selesai_sewa' => '2026-07-01',
            'total_biaya' => 1000000,
            'status_booking' => 'pending',
        ]);

        return [$booking, $pemilik];
    }
}
