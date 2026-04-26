<?php

declare(strict_types=1);

namespace Tests\Feature\Mitra;

use App\Livewire\Mitra\Dashboard;
use App\Models\Booking;
use App\Models\Kamar;
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

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_dynamic_stats_and_lists(): void
    {
        [$user, $kosan, $kontrakan, $bookingKosan, $bookingKontrakan] = $this->seedDashboardData();

        $this->actingAs($user);

        $response = $this->get(route('mitra.dashboard'));

        $response
            ->assertOk()
            ->assertSee('Dashboard Utama')
            ->assertSee($user->name)
            ->assertSee($user->email);

        Livewire::test(Dashboard::class)
            ->assertSee('Rp 900.000')
            ->assertSee('2 Properti')
            ->assertSee('2 Kamar')
            ->assertSee('2 Pesanan')
            ->assertSee('Kos Sakura')
            ->assertSee('Kontrakan Lily')
            ->assertSee('Siti Rahma')
            ->assertSee('Budi Santoso')
            ->assertSee('Kos Sakura (Kamar A1)')
            ->assertSee('Kontrakan Lily')
            ->assertSee('Lunas')
            ->assertSee('Menunggu');
    }

    public function test_konfirmasi_booking_updates_status_and_room(): void
    {
        [$user, , , $bookingKosan] = $this->seedDashboardData();

        $this->actingAs($user);

        Livewire::test(Dashboard::class)
            ->call('konfirmasiBooking', $bookingKosan->id)
            ->assertHasNoErrors();

        $bookingKosan->refresh();

        $this->assertSame('lunas', $bookingKosan->status_booking);
        $this->assertSame('dihuni', $bookingKosan->kamar->fresh()->status_kamar);
    }

    public function test_property_filter_uses_current_property_data(): void
    {
        [$user, $kosan, $kontrakan] = $this->seedDashboardData();

        $this->actingAs($user);

        Livewire::test(Dashboard::class)
            ->assertSee('ID KOS-'.str_pad((string) $kosan->id, 4, '0', STR_PAD_LEFT))
            ->assertSee('ID KTR-'.str_pad((string) $kontrakan->id, 4, '0', STR_PAD_LEFT))
            ->call('setFilterProperti', 'kosan')
            ->assertSet('filterProperti', 'kosan')
            ->assertSeeHtml('dashboard-property-kosan-'.$kosan->id)
            ->assertDontSeeHtml('dashboard-property-kontrakan-'.$kontrakan->id)
            ->call('setFilterProperti', 'kontrakan')
            ->assertSet('filterProperti', 'kontrakan')
            ->assertDontSeeHtml('dashboard-property-kosan-'.$kosan->id)
            ->assertSeeHtml('dashboard-property-kontrakan-'.$kontrakan->id);
    }

    public function test_tolak_booking_marks_booking_cancelled_and_restores_room(): void
    {
        [$user, , , $bookingKosan] = $this->seedDashboardData();

        $this->actingAs($user);

        $bookingKosan->kamar->update(['status_kamar' => 'dihuni']);

        Livewire::test(Dashboard::class)
            ->call('tolakBooking', $bookingKosan->id)
            ->assertHasNoErrors();

        $bookingKosan->refresh();

        $this->assertSame('batal', $bookingKosan->status_booking);
        $this->assertSame('tersedia', $bookingKosan->kamar->fresh()->status_kamar);
    }

    /**
     * @return array{0: User, 1: Kosan, 2: Kontrakan, 3: Booking, 4: Booking}
     */
    private function seedDashboardData(): array
    {
        $user = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'dashboard-mitra@example.com',
            'name' => 'Faldy Ardiansyah',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $user->id,
            'nama_bank' => 'Bank Test',
            'no_rekening' => '1234567890',
        ]);

        $kosan = Kosan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kos Sakura',
            'alamat_lengkap' => 'Bangkir, Indramayu',
            'latitude' => -6.4069,
            'longitude' => 108.2877,
            'peraturan_kos' => 'Wajib menjaga kebersihan',
        ]);

        $tipeAc = TipeKamar::create([
            'kosan_id' => $kosan->id,
            'nama_tipe' => 'Tipe AC',
            'harga_per_bulan' => 900000,
            'fasilitas_tipe' => 'AC, wifi',
        ]);

        $tipeFan = TipeKamar::create([
            'kosan_id' => $kosan->id,
            'nama_tipe' => 'Tipe Fan',
            'harga_per_bulan' => 650000,
            'fasilitas_tipe' => 'Kipas',
        ]);

        $kamarA1 = Kamar::create([
            'tipe_kamar_id' => $tipeAc->id,
            'nomor_kamar' => 'A1',
            'status_kamar' => 'tersedia',
        ]);

        Kamar::create([
            'tipe_kamar_id' => $tipeFan->id,
            'nomor_kamar' => 'B1',
            'status_kamar' => 'tersedia',
        ]);

        $kontrakan = Kontrakan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kontrakan Lily',
            'alamat_lengkap' => 'Bangkir, Indramayu',
            'latitude' => -6.41,
            'longitude' => 108.29,
            'harga_sewa_tahun' => 6000000,
            'fasilitas' => 'Garasi',
            'peraturan_kontrakan' => 'Tidak boleh renovasi',
            'sisa_kamar' => 1,
        ]);

        $pencariOne = $this->createPencari('siti-rahma@example.com', 'Siti Rahma', 'P');
        $pencariTwo = $this->createPencari('budi-santoso@example.com', 'Budi Santoso', 'L');

        $bookingKosan = Booking::create([
            'pencari_kos_id' => $pencariOne->id,
            'kamar_id' => $kamarA1->id,
            'kontrakan_id' => null,
            'tgl_mulai_sewa' => '2026-03-12',
            'tgl_selesai_sewa' => '2026-04-12',
            'total_biaya' => 900000,
            'status_booking' => 'pending',
        ]);

        Pembayaran::create([
            'booking_id' => $bookingKosan->id,
            'metode_bayar' => 'transfer',
            'waktu_bayar' => now(),
            'nominal_bayar' => 900000,
            'status_bayar' => 'lunas',
            'url_struk_pdf' => null,
        ]);

        $bookingKontrakan = Booking::create([
            'pencari_kos_id' => $pencariTwo->id,
            'kamar_id' => null,
            'kontrakan_id' => $kontrakan->id,
            'tgl_mulai_sewa' => '2026-04-01',
            'tgl_selesai_sewa' => '2027-04-01',
            'total_biaya' => 650000,
            'status_booking' => 'pending',
        ]);

        Pembayaran::create([
            'booking_id' => $bookingKontrakan->id,
            'metode_bayar' => 'transfer',
            'waktu_bayar' => null,
            'nominal_bayar' => 650000,
            'status_bayar' => 'pending',
            'url_struk_pdf' => null,
        ]);

        return [$user, $kosan, $kontrakan, $bookingKosan, $bookingKontrakan];
    }

    private function createPencari(string $email, string $name, string $gender): PencariKos
    {
        $user = User::factory()->create([
            'role' => 'pencari',
            'email' => $email,
            'name' => $name,
        ]);

        return PencariKos::create([
            'user_id' => $user->id,
            'jenis_kelamin' => $gender,
            'tanggal_lahir' => '2000-01-01',
            'pekerjaan' => 'Mahasiswa',
            'nama_instansi' => 'Polindra',
            'kota_asal' => 'Indramayu',
        ]);
    }
}
