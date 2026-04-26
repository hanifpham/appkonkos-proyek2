<?php

declare(strict_types=1);

namespace Tests\Feature\Mitra;

use App\Livewire\Mitra\RiwayatBooking;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Kosan;
use App\Models\Pembayaran;
use App\Models\PencariKos;
use App\Models\PemilikProperti;
use App\Models\Refund;
use App\Models\TipeKamar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RiwayatBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_only_sees_their_own_bookings_and_refund_has_zero_revenue(): void
    {
        [$user, $pemilik] = $this->createOwner('mitra-riwayat@example.com');
        [$otherUser, $otherPemilik] = $this->createOwner('mitra-riwayat-lain@example.com');

        $bookingSettlement = $this->createBookingForOwner($pemilik, 'Kos Mawar', 850000, 'settlement', 'A1');
        $bookingRefunded = $this->createBookingForOwner($pemilik, 'Kos Melati', 950000, 'refund', 'B2', 'batal');
        $this->createBookingForOwner($otherPemilik, 'Kos Rahasia', 700000, 'settlement', 'C3');

        Refund::create([
            'booking_id' => $bookingRefunded->id,
            'pembayaran_id' => $bookingRefunded->pembayaran->id,
            'nominal_refund' => 712500,
            'alasan_refund' => 'Penyewa membatalkan masa sewa.',
            'status_refund' => 'selesai',
        ]);

        $this->actingAs($user);

        Livewire::test(RiwayatBooking::class)
            ->assertSee('Kos Mawar')
            ->assertSee('Kos Melati')
            ->assertDontSee('Kos Rahasia')
            ->assertSee('SETTLEMENT')
            ->assertSee('REFUNDED')
            ->assertSee('Pendapatan Mitra: Rp 0')
            ->assertSee('Booking otomatis batal');
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
        ]);

        return [$user, $pemilik];
    }

    private function createBookingForOwner(
        PemilikProperti $pemilik,
        string $propertyName,
        int $nominal,
        string $paymentStatus,
        string $roomNumber,
        string $bookingStatus = 'lunas',
    ): Booking {
        $kosan = Kosan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => $propertyName,
            'alamat_lengkap' => 'Jl. Booking No. 1',
            'latitude' => -6.2,
            'longitude' => 106.8,
            'jenis_kos' => 'campur',
            'peraturan_kos' => 'Tertib',
        ]);

        $tipe = TipeKamar::create([
            'kosan_id' => $kosan->id,
            'nama_tipe' => 'Tipe '.$roomNumber,
            'harga_per_bulan' => $nominal,
            'fasilitas_tipe' => 'AC',
        ]);

        $kamar = Kamar::create([
            'tipe_kamar_id' => $tipe->id,
            'nomor_kamar' => $roomNumber,
            'status_kamar' => $paymentStatus === 'refund' ? 'tersedia' : 'dihuni',
        ]);

        $tenantUser = User::factory()->create([
            'role' => 'pencari',
            'email' => 'tenant-'.uniqid().'@example.com',
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
            'status_booking' => $bookingStatus,
        ]);

        Pembayaran::create([
            'booking_id' => $booking->id,
            'metode_bayar' => 'qris',
            'waktu_bayar' => now(),
            'nominal_bayar' => $nominal,
            'status_bayar' => $paymentStatus,
            'status_midtrans' => $paymentStatus,
            'midtrans_order_id' => 'ORDER-'.uniqid(),
        ]);

        return $booking->fresh(['pembayaran']);
    }
}
