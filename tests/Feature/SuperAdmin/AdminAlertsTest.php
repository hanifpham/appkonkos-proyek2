<?php

declare(strict_types=1);

namespace Tests\Feature\SuperAdmin;

use App\Models\Booking;
use App\Models\Kontrakan;
use App\Models\Pembayaran;
use App\Models\PencariKos;
use App\Models\PemilikProperti;
use App\Models\Refund;
use App\Models\User;
use App\Notifications\AdminAlert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAlertsTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_receives_database_alert_for_new_refund_request(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'email' => 'superadmin-refund-alert@example.com',
        ]);

        $pemilikUser = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'pemilik-refund-alert@example.com',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $pemilikUser->id,
            'nama_bank' => 'BCA',
            'nomor_rekening' => '123123123',
        ]);

        $kontrakan = Kontrakan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kontrakan Refund',
            'alamat_lengkap' => 'Jl. Refund No. 1',
            'latitude' => -6.2,
            'longitude' => 106.8,
            'harga_sewa_tahun' => 12000000,
            'fasilitas' => 'Wifi',
            'peraturan_kontrakan' => 'Tidak boleh merokok',
            'sisa_kamar' => 1,
            'status' => 'aktif',
        ]);

        $pencariUser = User::factory()->create([
            'role' => 'pencari',
            'email' => 'pencari-refund-alert@example.com',
        ]);

        $pencari = PencariKos::create([
            'user_id' => $pencariUser->id,
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
            'pekerjaan' => 'Mahasiswa',
            'nama_instansi' => 'Kampus Test',
            'kota_asal' => 'Bandung',
        ]);

        $booking = Booking::create([
            'pencari_kos_id' => $pencari->id,
            'kontrakan_id' => $kontrakan->id,
            'tgl_mulai_sewa' => now()->toDateString(),
            'tgl_selesai_sewa' => now()->addMonths(12)->toDateString(),
            'total_biaya' => 12000000,
            'status_booking' => 'lunas',
        ]);

        $pembayaran = Pembayaran::create([
            'booking_id' => $booking->id,
            'metode_bayar' => 'bank_transfer',
            'waktu_bayar' => now(),
            'nominal_bayar' => 12000000,
            'status_bayar' => 'settlement',
        ]);

        Refund::create([
            'booking_id' => $booking->id,
            'pembayaran_id' => $pembayaran->id,
            'nominal_refund' => 9000000,
            'alasan_refund' => 'Rencana pindah lokasi kerja.',
            'status_refund' => 'pending',
        ]);

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $superadmin->id,
            'notifiable_type' => User::class,
            'type' => AdminAlert::class,
        ]);
    }
}
