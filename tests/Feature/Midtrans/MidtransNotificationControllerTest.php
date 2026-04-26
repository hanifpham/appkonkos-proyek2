<?php

declare(strict_types=1);

namespace Tests\Feature\Midtrans;

use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Kosan;
use App\Models\Pembayaran;
use App\Models\PencariKos;
use App\Models\PemilikProperti;
use App\Models\TipeKamar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MidtransNotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_midtrans_notification_updates_payment_status(): void
    {
        $booking = $this->createBooking();

        $payment = Pembayaran::create([
            'booking_id' => $booking->id,
            'metode_bayar' => 'bank_transfer',
            'waktu_bayar' => null,
            'nominal_bayar' => 850000,
            'status_bayar' => 'pending',
            'url_struk_pdf' => null,
            'midtrans_order_id' => 'APPKONKOS-'.$booking->id.'-20260426120000',
        ]);

        $payload = [
            'order_id' => $payment->midtrans_order_id,
            'status_code' => '200',
            'gross_amount' => '850000.00',
            'transaction_status' => 'settlement',
            'transaction_id' => 'trx-001',
            'payment_type' => 'bank_transfer',
            'va_numbers' => [
                ['bank' => 'bca', 'va_number' => '1234567890'],
            ],
            'settlement_time' => '2026-04-26 12:15:00',
        ];

        $payload['signature_key'] = hash(
            'sha512',
            $payload['order_id'].$payload['status_code'].$payload['gross_amount'].config('services.midtrans.server_key')
        );

        $this->postJson(route('api.midtrans.notifications'), $payload)
            ->assertOk()
            ->assertJsonPath('status_bayar', 'lunas');

        $payment->refresh();

        $this->assertSame('lunas', $payment->status_bayar);
        $this->assertSame('settlement', $payment->status_midtrans);
        $this->assertSame('trx-001', $payment->midtrans_transaction_id);
        $this->assertSame('bank_transfer_bca', $payment->metode_bayar);
        $this->assertNotNull($payment->waktu_bayar);
    }

    public function test_midtrans_notification_rejects_invalid_signature(): void
    {
        $booking = $this->createBooking();

        Pembayaran::create([
            'booking_id' => $booking->id,
            'metode_bayar' => 'bank_transfer',
            'waktu_bayar' => null,
            'nominal_bayar' => 850000,
            'status_bayar' => 'pending',
            'url_struk_pdf' => null,
            'midtrans_order_id' => 'APPKONKOS-'.$booking->id.'-20260426120000',
        ]);

        $this->postJson(route('api.midtrans.notifications'), [
            'order_id' => 'APPKONKOS-'.$booking->id.'-20260426120000',
            'status_code' => '200',
            'gross_amount' => '850000.00',
            'transaction_status' => 'settlement',
            'signature_key' => 'invalid-signature',
        ])->assertForbidden();
    }

    private function createBooking(): Booking
    {
        $ownerUser = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'owner-midtrans@example.com',
        ]);

        $owner = PemilikProperti::create([
            'user_id' => $ownerUser->id,
            'nama_bank' => 'BCA',
            'nomor_rekening' => '1234567890',
        ]);

        $kosan = Kosan::create([
            'pemilik_properti_id' => $owner->id,
            'nama_properti' => 'Kos Melati',
            'alamat_lengkap' => 'Jl. Melati No. 1',
            'latitude' => -6.4,
            'longitude' => 108.2,
            'peraturan_kos' => 'Tertib',
        ]);

        $tipeKamar = TipeKamar::create([
            'kosan_id' => $kosan->id,
            'nama_tipe' => 'Standar',
            'harga_per_bulan' => 850000,
            'fasilitas_tipe' => 'Kasur',
        ]);

        $kamar = Kamar::create([
            'tipe_kamar_id' => $tipeKamar->id,
            'nomor_kamar' => 'A1',
            'status_kamar' => 'tersedia',
        ]);

        $pencari = $this->createPencari();

        return Booking::create([
            'pencari_kos_id' => $pencari->id,
            'kamar_id' => $kamar->id,
            'kontrakan_id' => null,
            'tgl_mulai_sewa' => '2026-05-01',
            'tgl_selesai_sewa' => '2026-06-01',
            'total_biaya' => 850000,
            'status_booking' => 'pending',
        ]);
    }

    private function createPencari(): PencariKos
    {
        $user = User::factory()->create([
            'role' => 'pencari',
            'email' => 'pencari-midtrans@example.com',
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
