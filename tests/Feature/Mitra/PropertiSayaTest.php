<?php

declare(strict_types=1);

namespace Tests\Feature\Mitra;

use App\Livewire\Mitra\PropertiSaya;
use App\Livewire\Mitra\TambahKontrakan;
use App\Livewire\Mitra\TambahKosan;
use App\Models\Kamar;
use App\Models\Kontrakan;
use App\Models\Kosan;
use App\Models\PemilikProperti;
use App\Models\TipeKamar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class PropertiSayaTest extends TestCase
{
    use RefreshDatabase;

    public function test_pemilik_can_filter_property_list_by_type(): void
    {
        $user = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'mitra-filter@example.com',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $user->id,
            'nama_bank' => 'Bank Test',
            'no_rekening' => '123123123',
        ]);

        $kosan = Kosan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kos Anggrek',
            'jenis_kos' => 'putri',
            'alamat_lengkap' => 'Jl. Anggrek 1',
            'latitude' => -5.4,
            'longitude' => 105.25,
            'peraturan_kos' => 'Tenang',
            'status' => 'ditolak',
            'alasan_penolakan' => 'Deskripsi properti belum lengkap.',
        ]);

        $tipeAc = TipeKamar::create([
            'kosan_id' => $kosan->id,
            'nama_tipe' => 'Tipe AC',
            'harga_per_bulan' => 900000,
            'fasilitas_tipe' => 'AC, wifi',
        ]);

        $tipeStandar = TipeKamar::create([
            'kosan_id' => $kosan->id,
            'nama_tipe' => 'Tipe Standar',
            'harga_per_bulan' => 650000,
            'fasilitas_tipe' => 'Kipas, kasur',
        ]);

        Kamar::create([
            'tipe_kamar_id' => $tipeAc->id,
            'nomor_kamar' => 'A1',
            'status_kamar' => 'tersedia',
        ]);

        Kamar::create([
            'tipe_kamar_id' => $tipeStandar->id,
            'nomor_kamar' => 'B1',
            'status_kamar' => 'tersedia',
        ]);

        Kontrakan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kontrakan Flamboyan',
            'alamat_lengkap' => 'Jl. Flamboyan 2',
            'latitude' => -5.41,
            'longitude' => 105.26,
            'harga_sewa_tahun' => 12000000,
            'fasilitas' => 'Garasi',
            'peraturan_kontrakan' => 'Tidak boleh renovasi',
            'sisa_kamar' => 1,
        ]);

        $this->actingAs($user);

        Livewire::test(PropertiSaya::class)
            ->assertSee('Kos Anggrek')
            ->assertSee('Kontrakan Flamboyan')
            ->assertSee('Kos Putri')
            ->assertSee('Kontrakan')
            ->assertSee('Ditolak')
            ->assertSee('Alasan Penolakan:')
            ->assertSee('Deskripsi properti belum lengkap.')
            ->assertSee('Edit & Ajukan Ulang')
            ->assertSee('Rp 650.000 - Rp 900.000')
            ->assertSee('/ bulan')
            ->assertSee('2 Unit')
            ->assertSee('Kelola Unit')
            ->call('setFilter', 'kosan')
            ->assertSet('filterTab', 'kosan')
            ->assertSee('Kos Anggrek')
            ->assertDontSee('Kontrakan Flamboyan')
            ->call('setFilter', 'kontrakan')
            ->assertSee('Kontrakan Flamboyan')
            ->assertDontSee('Kos Anggrek');
    }

    public function test_pemilik_can_create_kosan_from_tambah_kosan_component(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'mitra-kosan@example.com',
        ]);

        PemilikProperti::create([
            'user_id' => $user->id,
            'nama_bank' => 'Bank Test',
            'no_rekening' => '456456456',
        ]);

        $this->actingAs($user);

        $component = Livewire::test(TambahKosan::class)
            ->set('nama_properti', 'Kos Dahlia')
            ->set('jenis_kos', 'putri')
            ->set('alamat_lengkap', 'Jl. Dahlia No. 3')
            ->set('latitude', '-5.43000000')
            ->set('longitude', '105.27000000')
            ->set('peraturan_kos', 'Maksimal tamu sampai jam 10 malam')
            ->set('foto_properti', UploadedFile::fake()->image('kos-dahlia.png'))
            ->call('simpan')
            ->assertHasNoErrors();

        $kosan = Kosan::query()->where('nama_properti', 'Kos Dahlia')->first();

        $this->assertNotNull($kosan);
        $this->assertSame($user->pemilikProperti->id, $kosan->pemilik_properti_id);
        $this->assertSame('putri', $kosan->jenis_kos);
        $this->assertSame('Maksimal tamu sampai jam 10 malam', $kosan->peraturan_kos);
        $this->assertCount(1, $kosan->getMedia('foto_properti'));

        $component->assertRedirect(route('mitra.properti.kelola-kamar', ['kosan_id' => $kosan->id]));
    }

    public function test_jenis_kos_is_required_only_for_kosan_form(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'mitra-kosan-validasi@example.com',
        ]);

        PemilikProperti::create([
            'user_id' => $user->id,
            'nama_bank' => 'Bank Test',
            'no_rekening' => '654654654',
        ]);

        $this->actingAs($user);

        Livewire::test(TambahKosan::class)
            ->set('nama_properti', 'Kos Validasi')
            ->set('jenis_kos', '')
            ->set('alamat_lengkap', 'Jl. Validasi No. 8')
            ->set('latitude', '-6.33000000')
            ->set('longitude', '106.82000000')
            ->set('peraturan_kos', 'Aturan validasi')
            ->set('foto_properti', UploadedFile::fake()->image('kos-validasi.png'))
            ->call('simpan')
            ->assertHasErrors(['jenis_kos' => 'required'])
            ->assertSee('Kategori Kos wajib dipilih untuk properti kosan.');
    }

    public function test_pemilik_can_create_kontrakan_from_tambah_kontrakan_component(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'mitra-kontrakan@example.com',
        ]);

        PemilikProperti::create([
            'user_id' => $user->id,
            'nama_bank' => 'Bank Test',
            'no_rekening' => '789789789',
        ]);

        $this->actingAs($user);

        Livewire::test(TambahKontrakan::class)
            ->set('nama_properti', 'Kontrakan Kenanga')
            ->set('alamat_lengkap', 'Jl. Kenanga No. 7')
            ->set('latitude', '-5.44000000')
            ->set('longitude', '105.28000000')
            ->set('harga_sewa_tahun', '15000000')
            ->set('fasilitas', '2 kamar, dapur, carport')
            ->set('peraturan_kontrakan', 'Tidak boleh subkontrak')
            ->set('sisa_kamar', '1')
            ->set('foto_properti', UploadedFile::fake()->image('kontrakan-kenanga.png'))
            ->call('simpan')
            ->assertHasNoErrors();

        $kontrakan = Kontrakan::query()->where('nama_properti', 'Kontrakan Kenanga')->first();

        $this->assertNotNull($kontrakan);
        $this->assertSame($user->pemilikProperti->id, $kontrakan->pemilik_properti_id);
        $this->assertCount(1, $kontrakan->getMedia('foto_properti'));
    }

    public function test_replacing_kosan_photo_removes_old_media_record(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'mitra-kosan-replace@example.com',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $user->id,
            'nama_bank' => 'Bank Test',
            'no_rekening' => '123456789',
        ]);

        $kosan = Kosan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kos Melati',
            'jenis_kos' => 'putra',
            'alamat_lengkap' => 'Jl. Melati No. 9',
            'latitude' => -6.2,
            'longitude' => 106.8,
            'peraturan_kos' => 'Jaga kebersihan',
        ]);

        $oldMedia = $kosan
            ->addMedia(UploadedFile::fake()->image('kos-old.jpg'))
            ->toMediaCollection('foto_properti');

        $this->actingAs($user);

        Livewire::test(TambahKosan::class)
            ->set('editId', $kosan->id)
            ->set('nama_properti', 'Kos Melati')
            ->set('jenis_kos', 'putra')
            ->set('alamat_lengkap', 'Jl. Melati No. 9')
            ->set('latitude', '-6.20000000')
            ->set('longitude', '106.80000000')
            ->set('peraturan_kos', 'Jaga kebersihan')
            ->set('foto_properti', UploadedFile::fake()->image('kos-new.jpg'))
            ->call('simpan')
            ->assertHasNoErrors();

        $kosan->refresh();

        $this->assertCount(1, $kosan->getMedia('foto_properti'));
        $this->assertDatabaseMissing('media', ['id' => $oldMedia->id]);
    }

    public function test_replacing_kontrakan_photo_removes_old_media_record(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'mitra-kontrakan-replace@example.com',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $user->id,
            'nama_bank' => 'Bank Test',
            'no_rekening' => '999888777',
        ]);

        $kontrakan = Kontrakan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kontrakan Mawar',
            'alamat_lengkap' => 'Jl. Mawar No. 4',
            'latitude' => -6.21,
            'longitude' => 106.81,
            'harga_sewa_tahun' => 12000000,
            'fasilitas' => '2 kamar',
            'peraturan_kontrakan' => 'Tidak boleh subkontrak',
            'sisa_kamar' => 1,
        ]);

        $oldMedia = $kontrakan
            ->addMedia(UploadedFile::fake()->image('kontrakan-old.jpg'))
            ->toMediaCollection('foto_properti');

        $this->actingAs($user);

        Livewire::test(TambahKontrakan::class)
            ->set('editId', $kontrakan->id)
            ->set('nama_properti', 'Kontrakan Mawar')
            ->set('alamat_lengkap', 'Jl. Mawar No. 4')
            ->set('latitude', '-6.21000000')
            ->set('longitude', '106.81000000')
            ->set('harga_sewa_tahun', '12000000')
            ->set('fasilitas', '2 kamar')
            ->set('peraturan_kontrakan', 'Tidak boleh subkontrak')
            ->set('sisa_kamar', '1')
            ->set('foto_properti', UploadedFile::fake()->image('kontrakan-new.jpg'))
            ->call('simpan')
            ->assertHasNoErrors();

        $kontrakan->refresh();

        $this->assertCount(1, $kontrakan->getMedia('foto_properti'));
        $this->assertDatabaseMissing('media', ['id' => $oldMedia->id]);
    }

    public function test_editing_rejected_kosan_resets_status_to_pending_and_clears_rejection_reason(): void
    {
        $user = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'mitra-kosan-resubmit@example.com',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $user->id,
            'nama_bank' => 'Bank Test',
            'no_rekening' => '121212121',
        ]);

        $kosan = Kosan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kos Resubmit',
            'jenis_kos' => 'putra',
            'alamat_lengkap' => 'Jl. Resubmit No. 1',
            'latitude' => -6.1,
            'longitude' => 106.7,
            'peraturan_kos' => 'Aturan lama',
            'status' => 'ditolak',
            'alasan_penolakan' => 'Data belum lengkap.',
        ]);

        $this->actingAs($user);

        Livewire::test(TambahKosan::class)
            ->set('editId', $kosan->id)
            ->set('nama_properti', 'Kos Resubmit')
            ->set('jenis_kos', 'putra')
            ->set('alamat_lengkap', 'Jl. Resubmit No. 1')
            ->set('latitude', '-6.10000000')
            ->set('longitude', '106.70000000')
            ->set('peraturan_kos', 'Aturan sudah diperbarui')
            ->call('simpan')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('kosan', [
            'id' => $kosan->id,
            'status' => 'pending',
            'alasan_penolakan' => null,
        ]);
    }

    public function test_editing_rejected_kontrakan_resets_status_to_pending_and_clears_rejection_reason(): void
    {
        $user = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'mitra-kontrakan-resubmit@example.com',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $user->id,
            'nama_bank' => 'Bank Test',
            'no_rekening' => '343434343',
        ]);

        $kontrakan = Kontrakan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kontrakan Resubmit',
            'alamat_lengkap' => 'Jl. Resubmit No. 2',
            'latitude' => -6.11,
            'longitude' => 106.71,
            'harga_sewa_tahun' => 13000000,
            'fasilitas' => 'Fasilitas lama',
            'peraturan_kontrakan' => 'Peraturan lama',
            'sisa_kamar' => 1,
            'status' => 'ditolak',
            'alasan_penolakan' => 'Foto properti kurang jelas.',
        ]);

        $this->actingAs($user);

        Livewire::test(TambahKontrakan::class)
            ->set('editId', $kontrakan->id)
            ->set('nama_properti', 'Kontrakan Resubmit')
            ->set('alamat_lengkap', 'Jl. Resubmit No. 2')
            ->set('latitude', '-6.11000000')
            ->set('longitude', '106.71000000')
            ->set('harga_sewa_tahun', '13000000')
            ->set('fasilitas', 'Fasilitas diperbarui')
            ->set('peraturan_kontrakan', 'Peraturan diperbarui')
            ->set('sisa_kamar', '1')
            ->call('simpan')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('kontrakan', [
            'id' => $kontrakan->id,
            'status' => 'pending',
            'alasan_penolakan' => null,
        ]);
    }
}
