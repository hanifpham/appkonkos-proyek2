<?php

declare(strict_types=1);

namespace Tests\Feature\Mitra;

use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Kosan;
use App\Models\Pembayaran;
use App\Models\PencariKos;
use App\Models\PemilikProperti;
use App\Models\PencairanDana;
use App\Models\TipeKamar;
use App\Models\User;
use App\Notifications\PencairanStatusNotification;
use App\Notifications\PropertiDitolakNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotifikasiTest extends TestCase
{
    use RefreshDatabase;

    public function test_mitra_notification_page_shows_only_current_user_notifications(): void
    {
        [$user, $pemilik] = $this->createOwner('mitra-notifikasi-page@example.com');
        [$otherUser] = $this->createOwner('mitra-notifikasi-page-lain@example.com');

        $kosan = Kosan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kos Notifikasi Mitra',
            'alamat_lengkap' => 'Jl. Notifikasi No. 1',
            'latitude' => -6.2,
            'longitude' => 106.8,
            'jenis_kos' => 'campur',
            'peraturan_kos' => 'Lengkap',
            'status' => 'ditolak',
        ]);

        $this->createSettlementPaymentForOwner($pemilik, 'Kos Booking Baru');

        $pencairan = PencairanDana::create([
            'pemilik_properti_id' => $pemilik->id,
            'nominal' => 300000,
            'nama_bank_tujuan' => 'BCA',
            'nomor_rekening_tujuan' => '1234567890',
            'atas_nama_tujuan' => 'Mitra Notifikasi',
            'status' => 'ditolak',
            'catatan_admin' => 'Nomor rekening tidak valid.',
        ]);

        $user->notify(new PencairanStatusNotification($pencairan, 'ditolak', 'Nomor rekening tidak valid.'));
        $user->notify(new PropertiDitolakNotification($kosan, 'Foto properti belum jelas.', 'Properti Diturunkan'));

        $this->actingAs($user);

        $this->get(route('mitra.notifikasi'))
            ->assertOk()
            ->assertSee('Booking Settlement Baru')
            ->assertSee('Pencairan Ditolak')
            ->assertSee('Properti Diturunkan')
            ->assertSee('Nomor rekening tidak valid.')
            ->assertDontSee($otherUser->email);
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
            'nama_pemilik_rekening' => 'Mitra Notifikasi',
        ]);

        return [$user, $pemilik];
    }

    private function createSettlementPaymentForOwner(PemilikProperti $pemilik, string $propertyName): void
    {
        $kosan = Kosan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => $propertyName,
            'alamat_lengkap' => 'Jl. Booking No. 2',
            'latitude' => -6.2,
            'longitude' => 106.8,
            'jenis_kos' => 'campur',
            'peraturan_kos' => 'Tertib',
        ]);

        $tipe = TipeKamar::create([
            'kosan_id' => $kosan->id,
            'nama_tipe' => 'Tipe A',
            'harga_per_bulan' => 900000,
            'fasilitas_tipe' => 'AC',
        ]);

        $kamar = Kamar::create([
            'tipe_kamar_id' => $tipe->id,
            'nomor_kamar' => 'A1',
            'status_kamar' => 'dihuni',
        ]);

        $tenantUser = User::factory()->create([
            'role' => 'pencari',
            'email' => 'tenant-notif-'.uniqid().'@example.com',
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
            'total_biaya' => 900000,
            'status_booking' => 'lunas',
        ]);

        Pembayaran::create([
            'booking_id' => $booking->id,
            'metode_bayar' => 'qris',
            'waktu_bayar' => now(),
            'nominal_bayar' => 900000,
            'status_bayar' => 'settlement',
            'status_midtrans' => 'settlement',
            'midtrans_order_id' => 'ORDER-'.uniqid(),
        ]);
    }
}
