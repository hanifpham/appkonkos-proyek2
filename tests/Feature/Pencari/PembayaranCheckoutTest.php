<?php

declare(strict_types=1);

namespace Tests\Feature\Pencari;

use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Kosan;
use App\Models\Pembayaran;
use App\Models\PencariKos;
use App\Models\PemilikProperti;
use App\Models\TipeKamar;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PembayaranCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_pencari_can_view_payment_page_for_own_booking(): void
    {
        [$user, $booking] = $this->createOwnedBooking();

        $this->actingAs($user);

        $this->get(route('pencari.pembayaran.show', $booking))
            ->assertOk()
            ->assertSee('Pembayaran Booking')
            ->assertSee('Kos Anggrek - Kamar C3')
            ->assertSee('Bayar Sekarang');
    }

    public function test_pencari_can_request_snap_token_for_own_booking(): void
    {
        [$user, $booking] = $this->createOwnedBooking();

        $payment = Pembayaran::create([
            'booking_id' => $booking->id,
            'metode_bayar' => null,
            'waktu_bayar' => null,
            'nominal_bayar' => 975000,
            'status_bayar' => 'pending',
            'url_struk_pdf' => null,
            'midtrans_order_id' => 'APPKONKOS-'.$booking->id.'-20260426123000',
            'snap_token' => 'snap-token-123',
            'snap_redirect_url' => 'https://example.test/redirect',
        ]);

        $mock = Mockery::mock(MidtransService::class);
        $mock->shouldReceive('createOrRefreshTransaction')
            ->once()
            ->withArgs(fn (Booking $incomingBooking): bool => $incomingBooking->is($booking))
            ->andReturn($payment);

        $this->instance(MidtransService::class, $mock);
        $this->actingAs($user);

        $this->postJson(route('pencari.pembayaran.snap-token', $booking))
            ->assertOk()
            ->assertJsonPath('snap_token', 'snap-token-123')
            ->assertJsonPath('status_bayar', 'pending');
    }

    /**
     * @return array{0: User, 1: Booking}
     */
    private function createOwnedBooking(): array
    {
        $pencariUser = User::factory()->create([
            'role' => 'pencari',
            'email' => 'checkout-pencari@example.com',
        ]);

        $pencari = PencariKos::create([
            'user_id' => $pencariUser->id,
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '2000-01-01',
            'pekerjaan' => 'Mahasiswa',
            'nama_instansi' => 'Polindra',
            'kota_asal' => 'Indramayu',
        ]);

        $ownerUser = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'checkout-owner@example.com',
        ]);

        $owner = PemilikProperti::create([
            'user_id' => $ownerUser->id,
            'nama_bank' => 'BCA',
            'nomor_rekening' => '123456789',
        ]);

        $kosan = Kosan::create([
            'pemilik_properti_id' => $owner->id,
            'nama_properti' => 'Kos Anggrek',
            'alamat_lengkap' => 'Jl. Anggrek No. 8',
            'latitude' => -6.4,
            'longitude' => 108.2,
            'peraturan_kos' => 'Tertib',
        ]);

        $tipeKamar = TipeKamar::create([
            'kosan_id' => $kosan->id,
            'nama_tipe' => 'Deluxe',
            'harga_per_bulan' => 975000,
            'fasilitas_tipe' => 'AC',
        ]);

        $kamar = Kamar::create([
            'tipe_kamar_id' => $tipeKamar->id,
            'nomor_kamar' => 'C3',
            'status_kamar' => 'tersedia',
        ]);

        $booking = Booking::create([
            'pencari_kos_id' => $pencari->id,
            'kamar_id' => $kamar->id,
            'kontrakan_id' => null,
            'tgl_mulai_sewa' => '2026-05-01',
            'tgl_selesai_sewa' => '2026-06-01',
            'total_biaya' => 975000,
            'status_booking' => 'pending',
        ]);

        return [$pencariUser, $booking];
    }
}
