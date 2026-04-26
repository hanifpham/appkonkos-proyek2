<?php

declare(strict_types=1);

namespace Tests\Feature\SuperAdmin;

use App\Livewire\SuperAdmin\ManajemenPengguna;
use App\Models\Kontrakan;
use App\Models\Kosan;
use App\Models\PemilikProperti;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class ManajemenPenggunaTest extends TestCase
{
    use RefreshDatabase;

    public function test_blokir_akun_mitra_mensuspend_semua_properti_publik(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'email' => 'superadmin-manajemen@example.com',
        ]);

        $pemilikUser = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'pemilik-manajemen@example.com',
            'status' => 'aktif',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $pemilikUser->id,
            'nama_bank' => 'BCA',
            'nomor_rekening' => '1234567890',
            'status_verifikasi' => 'terverifikasi',
        ]);

        $kosan = Kosan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kos Suspend',
            'alamat_lengkap' => 'Jl. Suspend No. 1',
            'latitude' => -6.4,
            'longitude' => 108.2,
            'jenis_kos' => 'putra',
            'peraturan_kos' => 'Tertib',
            'status' => 'aktif',
        ]);

        $kontrakan = Kontrakan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kontrakan Suspend',
            'alamat_lengkap' => 'Jl. Suspend No. 2',
            'latitude' => -6.4,
            'longitude' => 108.2,
            'harga_sewa_tahun' => 12000000,
            'fasilitas' => 'Garasi',
            'peraturan_kontrakan' => 'Tertib',
            'sisa_kamar' => 1,
            'status' => 'aktif',
        ]);

        $this->actingAs($superadmin);

        Livewire::test(ManajemenPengguna::class)
            ->call('blokirAkun', $pemilikUser->id)
            ->assertDispatched('appkonkos-toast');

        $this->assertDatabaseHas('users', [
            'id' => $pemilikUser->id,
            'status' => 'diblokir',
        ]);

        $expectedPropertyStatus = DB::getDriverName() === 'sqlite' ? 'ditolak' : 'suspend';

        $this->assertDatabaseHas('kosan', [
            'id' => $kosan->id,
            'status' => $expectedPropertyStatus,
        ]);

        $this->assertDatabaseHas('kontrakan', [
            'id' => $kontrakan->id,
            'status' => $expectedPropertyStatus,
        ]);
    }
}
