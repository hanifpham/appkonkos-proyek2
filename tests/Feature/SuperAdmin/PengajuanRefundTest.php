<?php

declare(strict_types=1);

namespace Tests\Feature\SuperAdmin;

use App\Livewire\SuperAdmin\PengajuanRefund;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Kosan;
use App\Models\Pembayaran;
use App\Models\PencariKos;
use App\Models\PemilikProperti;
use App\Models\Refund;
use App\Models\Setting;
use App\Models\TipeKamar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

class PengajuanRefundTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_review_and_process_partial_refund(): void
    {
        config([
            'midtrans.server_key' => 'test-server-key',
            'midtrans.is_production' => false,
        ]);

        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'email' => 'superadmin-refund@example.com',
        ]);

        [$booking, $payment] = $this->createBookingAndPayment();

        $refund = Refund::create([
            'booking_id' => $booking->id,
            'pembayaran_id' => $payment->id,
            'nominal_refund' => 1000000,
            'alasan_refund' => 'Perubahan rencana tinggal.',
            'status_refund' => 'pending',
        ]);

        Setting::putValue(Setting::KEY_REFUND_DEDUCTION, 30);

        Mockery::mock('alias:Midtrans\Transaction')
            ->shouldReceive('refund')
            ->once()
            ->with('ORDER-REFUND-001', Mockery::on(function (array $params): bool {
                return $params['refund_key'] === 'ref-ORDER-REFUND-001'
                    && $params['amount'] === 700000
                    && $params['reason'] === 'Refund Parsial 70%';
            }))
            ->andReturn((object) [
                'transaction_status' => 'refund',
            ]);

        $this->actingAs($superadmin);

        Livewire::test(PengajuanRefund::class)
            ->call('tinjauRefund', $refund->id)
            ->assertSet('showModalRefund', true)
            ->assertSet('potonganRefundPersen', 30.0)
            ->assertSet('totalPotongan', 300000)
            ->assertSet('totalKembali', 700000)
            ->call('prosesRefund')
            ->assertDispatched('appkonkos-toast');

        $this->assertDatabaseHas('refund', [
            'id' => $refund->id,
            'status_refund' => 'selesai',
            'nominal_refund' => 700000,
        ]);

        $this->assertDatabaseHas('pembayaran', [
            'id' => $payment->id,
            'status_bayar' => 'refund',
            'status_midtrans' => 'refund',
        ]);

        $this->assertDatabaseHas('booking', [
            'id' => $booking->id,
            'status_booking' => 'batal',
        ]);

        $this->assertDatabaseHas('kamar', [
            'id' => $booking->kamar_id,
            'status_kamar' => 'tersedia',
        ]);
    }

    /**
     * @return array{0: Booking, 1: Pembayaran}
     */
    private function createBookingAndPayment(): array
    {
        $pemilikUser = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'pemilik-refund@example.com',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $pemilikUser->id,
            'nama_bank' => 'BCA',
            'nomor_rekening' => '123456789',
            'status_verifikasi' => 'terverifikasi',
        ]);

        $kosan = Kosan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kos Refund',
            'alamat_lengkap' => 'Jl. Refund No. 1',
            'latitude' => -6.4,
            'longitude' => 108.2,
            'jenis_kos' => 'putri',
            'peraturan_kos' => 'Tertib',
        ]);

        $tipeKamar = TipeKamar::create([
            'kosan_id' => $kosan->id,
            'nama_tipe' => 'VIP',
            'harga_per_bulan' => 1000000,
            'fasilitas_tipe' => 'AC',
        ]);

        $kamar = Kamar::create([
            'tipe_kamar_id' => $tipeKamar->id,
            'nomor_kamar' => 'A1',
            'status_kamar' => 'dihuni',
        ]);

        $pencariUser = User::factory()->create([
            'role' => 'pencari',
            'email' => 'pencari-refund@example.com',
        ]);

        $pencari = PencariKos::create([
            'user_id' => $pencariUser->id,
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '2000-01-01',
            'pekerjaan' => 'Mahasiswa',
            'nama_instansi' => 'Polindra',
            'kota_asal' => 'Indramayu',
        ]);

        $booking = Booking::create([
            'pencari_kos_id' => $pencari->id,
            'kamar_id' => $kamar->id,
            'kontrakan_id' => null,
            'tgl_mulai_sewa' => '2026-06-01',
            'tgl_selesai_sewa' => '2026-07-01',
            'total_biaya' => 1000000,
            'status_booking' => 'lunas',
        ]);

        $payment = Pembayaran::create([
            'booking_id' => $booking->id,
            'metode_bayar' => 'qris',
            'waktu_bayar' => now(),
            'nominal_bayar' => 1000000,
            'status_bayar' => 'settlement',
            'url_struk_pdf' => null,
            'midtrans_order_id' => 'ORDER-REFUND-001',
            'status_midtrans' => 'settlement',
        ]);

        return [$booking, $payment];
    }
}
