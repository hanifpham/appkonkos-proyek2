<?php

declare(strict_types=1);

namespace Tests\Feature\Mitra;

use App\Livewire\Mitra\Keuangan;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Kosan;
use App\Models\Pembayaran;
use App\Models\PencariKos;
use App\Models\PemilikProperti;
use App\Models\PencairanDana;
use App\Models\Setting;
use App\Models\TipeKamar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class KeuanganTest extends TestCase
{
    use RefreshDatabase;

    public function test_mitra_finance_page_uses_net_income_and_successful_withdrawals_only(): void
    {
        Setting::putValue(Setting::KEY_PLATFORM_COMMISSION, 10);

        [$user, $pemilik] = $this->createOwner('mitra-keuangan@example.com');
        [$otherUser, $otherPemilik] = $this->createOwner('mitra-lain@example.com');

        $this->createSuccessfulPaymentForOwner($pemilik, 1000000);
        $this->createSuccessfulPaymentForOwner($otherPemilik, 800000);

        PencairanDana::create([
            'pemilik_properti_id' => $pemilik->id,
            'nominal' => 250000,
            'nama_bank_tujuan' => 'BCA',
            'nomor_rekening_tujuan' => '1234567890',
            'atas_nama_tujuan' => 'Mitra Keuangan',
            'status' => 'sukses',
        ]);

        PencairanDana::create([
            'pemilik_properti_id' => $pemilik->id,
            'nominal' => 125000,
            'nama_bank_tujuan' => 'BCA',
            'nomor_rekening_tujuan' => '1234567890',
            'atas_nama_tujuan' => 'Mitra Keuangan',
            'status' => 'pending',
        ]);

        PencairanDana::create([
            'pemilik_properti_id' => $otherPemilik->id,
            'nominal' => 900000,
            'nama_bank_tujuan' => 'BRI',
            'nomor_rekening_tujuan' => '999999999',
            'atas_nama_tujuan' => 'Mitra Lain',
            'status' => 'sukses',
        ]);

        $this->actingAs($user);

        Livewire::test(Keuangan::class)
            ->assertSee('Rp 650.000')
            ->assertSee('Rp 900.000')
            ->assertSee('Rp 250.000')
            ->assertSee('Rp 125.000')
            ->assertDontSee($otherUser->email);
    }

    public function test_mitra_can_submit_withdrawal_request_with_destination_account_fields(): void
    {
        Setting::putValue(Setting::KEY_PLATFORM_COMMISSION, 5);

        [$user, $pemilik] = $this->createOwner('mitra-request@example.com');
        $this->createSuccessfulPaymentForOwner($pemilik, 1000000);

        $this->actingAs($user);

        Livewire::test(Keuangan::class)
            ->set('namaBank', 'Mandiri')
            ->set('nomorRekening', '888777666')
            ->set('atasNama', 'Pemilik Kos')
            ->set('nominalPencairan', 500000)
            ->call('mintaPencairan')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('pencairan_dana', [
            'pemilik_properti_id' => $pemilik->id,
            'nominal' => 500000,
            'nama_bank_tujuan' => 'Mandiri',
            'nomor_rekening_tujuan' => '888777666',
            'atas_nama_tujuan' => 'Pemilik Kos',
            'status' => 'pending',
        ]);
    }

    public function test_mitra_can_see_rejected_withdrawal_reason_in_history(): void
    {
        [$user, $pemilik] = $this->createOwner('mitra-rejected@example.com');

        PencairanDana::create([
            'pemilik_properti_id' => $pemilik->id,
            'nominal' => 350000,
            'nama_bank_tujuan' => 'BCA',
            'nomor_rekening_tujuan' => '1234567890',
            'atas_nama_tujuan' => 'Mitra Keuangan',
            'status' => 'ditolak',
            'catatan_admin' => 'Nama pemilik rekening tidak sesuai.',
        ]);

        $this->actingAs($user);

        Livewire::test(Keuangan::class)
            ->assertSee('#WD-00001')
            ->assertSee('Ditolak')
            ->assertSee('Alasan Penolakan: Nama pemilik rekening tidak sesuai.');
    }

    /**
     * @return array{0: User, 1: PemilikProperti}
     */
    private function createOwner(string $email): array
    {
        $user = User::factory()->create([
            'role' => 'pemilik',
            'email' => $email,
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $user->id,
            'nama_bank' => 'BCA',
            'nomor_rekening' => '1234567890',
            'nama_pemilik_rekening' => 'Mitra Keuangan',
        ]);

        return [$user, $pemilik];
    }

    private function createSuccessfulPaymentForOwner(PemilikProperti $pemilik, int $nominal): void
    {
        $kosan = Kosan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kos Keuangan '.$pemilik->id,
            'alamat_lengkap' => 'Jl. Keuangan No. 1',
            'latitude' => -6.2,
            'longitude' => 106.8,
            'jenis_kos' => 'campur',
            'peraturan_kos' => 'Tertib',
        ]);

        $tipe = TipeKamar::create([
            'kosan_id' => $kosan->id,
            'nama_tipe' => 'Tipe A',
            'harga_per_bulan' => $nominal,
            'fasilitas_tipe' => 'AC',
        ]);

        $kamar = Kamar::create([
            'tipe_kamar_id' => $tipe->id,
            'nomor_kamar' => 'A1',
            'status_kamar' => 'dihuni',
        ]);

        $tenantUser = User::factory()->create([
            'role' => 'pencari',
            'email' => 'tenant-'.$pemilik->id.'-'.uniqid().'@example.com',
        ]);

        $tenant = PencariKos::create([
            'user_id' => $tenantUser->id,
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
            'pekerjaan' => 'Mahasiswa',
            'nama_instansi' => 'Polindra',
            'kota_asal' => 'Indramayu',
        ]);

        $booking = Booking::create([
            'pencari_kos_id' => $tenant->id,
            'kamar_id' => $kamar->id,
            'kontrakan_id' => null,
            'tgl_mulai_sewa' => now()->toDateString(),
            'tgl_selesai_sewa' => now()->addMonth()->toDateString(),
            'total_biaya' => $nominal,
            'status_booking' => 'lunas',
        ]);

        Pembayaran::create([
            'booking_id' => $booking->id,
            'metode_bayar' => 'qris',
            'waktu_bayar' => now(),
            'nominal_bayar' => $nominal,
            'status_bayar' => 'settlement',
            'status_midtrans' => 'settlement',
            'midtrans_order_id' => 'ORDER-'.uniqid(),
        ]);
    }
}
