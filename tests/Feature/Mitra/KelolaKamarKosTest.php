<?php

declare(strict_types=1);

namespace Tests\Feature\Mitra;

use App\Livewire\Mitra\KelolaKamarKos;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Kosan;
use App\Models\PencariKos;
use App\Models\PemilikProperti;
use App\Models\TipeKamar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class KelolaKamarKosTest extends TestCase
{
    use RefreshDatabase;

    public function test_pemilik_can_create_room_types_and_specific_rooms(): void
    {
        Storage::fake('public');

        [$user, $kosan] = $this->createPemilikAndKosan();

        $this->actingAs($user);

        $pngImage = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO7Z0ioAAAAASUVORK5CYII=',
            true
        );

        Livewire::test(KelolaKamarKos::class, ['kosan_id' => $kosan->id])
            ->set('nama_tipe', 'Tipe AC')
            ->set('harga_per_bulan', '950000')
            ->set('fasilitas_tipe', 'AC, wifi, kamar mandi dalam')
            ->set('foto_interior', UploadedFile::fake()->createWithContent('interior-ac.png', $pngImage))
            ->call('tambahTipeKamar')
            ->assertHasNoErrors();

        $tipeKamar = TipeKamar::query()->where('kosan_id', $kosan->id)->first();

        $this->assertNotNull($tipeKamar);
        $this->assertCount(1, $tipeKamar->getMedia('foto_interior'));

        Livewire::test(KelolaKamarKos::class, ['kosan_id' => $kosan->id])
            ->set("roomInputs.{$tipeKamar->id}", ['A1', 'A2'])
            ->call('simpanKamar', $tipeKamar->id)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('kamar', [
            'tipe_kamar_id' => $tipeKamar->id,
            'nomor_kamar' => 'A1',
            'status_kamar' => 'tersedia',
        ]);

        $this->assertDatabaseHas('kamar', [
            'tipe_kamar_id' => $tipeKamar->id,
            'nomor_kamar' => 'A2',
            'status_kamar' => 'tersedia',
        ]);
    }

    public function test_nomor_kamar_must_be_unique_within_a_kosan(): void
    {
        [$user, $kosan] = $this->createPemilikAndKosan();

        $this->actingAs($user);

        $tipeAc = TipeKamar::create([
            'kosan_id' => $kosan->id,
            'nama_tipe' => 'Tipe AC',
            'harga_per_bulan' => 950000,
            'fasilitas_tipe' => 'AC',
        ]);

        $tipeFan = TipeKamar::create([
            'kosan_id' => $kosan->id,
            'nama_tipe' => 'Tipe Fan',
            'harga_per_bulan' => 650000,
            'fasilitas_tipe' => 'Kipas',
        ]);

        Kamar::create([
            'tipe_kamar_id' => $tipeAc->id,
            'nomor_kamar' => 'A1',
            'status_kamar' => 'tersedia',
        ]);

        Livewire::test(KelolaKamarKos::class, ['kosan_id' => $kosan->id])
            ->set("roomInputs.{$tipeFan->id}", ['A1'])
            ->call('simpanKamar', $tipeFan->id)
            ->assertHasErrors(['roomInputs.'.$tipeFan->id]);

        $this->assertDatabaseMissing('kamar', [
            'tipe_kamar_id' => $tipeFan->id,
            'nomor_kamar' => 'A1',
        ]);
    }

    public function test_room_or_type_cannot_be_deleted_when_booking_exists(): void
    {
        [$user, $kosan] = $this->createPemilikAndKosan();

        $this->actingAs($user);

        $tipeKamar = TipeKamar::create([
            'kosan_id' => $kosan->id,
            'nama_tipe' => 'Tipe AC',
            'harga_per_bulan' => 950000,
            'fasilitas_tipe' => 'AC',
        ]);

        $kamar = Kamar::create([
            'tipe_kamar_id' => $tipeKamar->id,
            'nomor_kamar' => 'A1',
            'status_kamar' => 'dihuni',
        ]);

        $pencariUser = User::factory()->create([
            'role' => 'pencari',
            'email' => 'pencari@example.com',
        ]);

        $pencariKos = PencariKos::create([
            'user_id' => $pencariUser->id,
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
            'pekerjaan' => 'Mahasiswa',
            'nama_instansi' => 'Polindra',
            'kota_asal' => 'Indramayu',
        ]);

        Booking::create([
            'pencari_kos_id' => $pencariKos->id,
            'kamar_id' => $kamar->id,
            'kontrakan_id' => null,
            'tgl_mulai_sewa' => '2026-04-01',
            'tgl_selesai_sewa' => '2026-05-01',
            'total_biaya' => 950000,
            'status_booking' => 'pending',
        ]);

        Livewire::test(KelolaKamarKos::class, ['kosan_id' => $kosan->id])
            ->call('hapusKamar', $kamar->id)
            ->assertHasErrors(['general'])
            ->call('hapusTipeKamar', $tipeKamar->id)
            ->assertHasErrors(['general']);

        $this->assertDatabaseHas('kamar', ['id' => $kamar->id]);
        $this->assertDatabaseHas('tipe_kamar', ['id' => $tipeKamar->id]);
    }

    public function test_pemilik_can_finish_room_setup_and_return_to_property_list(): void
    {
        [$user, $kosan] = $this->createPemilikAndKosan();

        $this->actingAs($user);

        TipeKamar::create([
            'kosan_id' => $kosan->id,
            'nama_tipe' => 'Tipe AC',
            'harga_per_bulan' => 950000,
            'fasilitas_tipe' => 'AC',
        ]);

        Livewire::test(KelolaKamarKos::class, ['kosan_id' => $kosan->id])
            ->call('selesai')
            ->assertHasNoErrors()
            ->assertRedirect(route('mitra.properti'));
    }

    public function test_pemilik_cannot_finish_when_there_are_unsaved_room_inputs(): void
    {
        [$user, $kosan] = $this->createPemilikAndKosan();

        $this->actingAs($user);

        $tipeKamar = TipeKamar::create([
            'kosan_id' => $kosan->id,
            'nama_tipe' => 'Tipe AC',
            'harga_per_bulan' => 950000,
            'fasilitas_tipe' => 'AC',
        ]);

        Livewire::test(KelolaKamarKos::class, ['kosan_id' => $kosan->id])
            ->set("roomInputs.{$tipeKamar->id}", ['A1'])
            ->call('selesai')
            ->assertHasErrors(['general']);
    }

    /**
     * @return array{0: User, 1: Kosan}
     */
    private function createPemilikAndKosan(): array
    {
        $user = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'pemilik-kelola@example.com',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $user->id,
            'nama_bank' => 'Bank Test',
            'no_rekening' => '1234567890',
        ]);

        $kosan = Kosan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kos Melati',
            'alamat_lengkap' => 'Jl. Melati No. 10',
            'latitude' => -6.40690782,
            'longitude' => 108.28776285,
            'peraturan_kos' => 'Wajib menjaga kebersihan',
        ]);

        return [$user, $kosan];
    }
}
