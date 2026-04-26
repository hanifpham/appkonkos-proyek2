<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PemilikProperti;
use App\Models\PencariKos;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@appkonkos.test'],
            [
                'name' => 'Super Admin APPKONKOS',
                'no_telepon' => '081111111111',
                'role' => 'superadmin',
                'status_akun' => true,
                'email_verified_at' => now(),
                'password' => 'password',
            ]
        );

        $pemilikUser = User::updateOrCreate(
            ['email' => 'pemilik@appkonkos.test'],
            [
                'name' => 'Pemilik APPKONKOS',
                'no_telepon' => '082222222222',
                'role' => 'pemilik',
                'status_akun' => true,
                'email_verified_at' => now(),
                'password' => 'password',
            ]
        );

        PemilikProperti::updateOrCreate(
            ['user_id' => $pemilikUser->id],
            [
                'alamat_domisili' => 'Jl. Contoh No. 1, Indramayu, Jawa Barat',
                'nik_ktp' => '3212345678901234',
                'nama_bank' => 'BCA',
                'nomor_rekening' => '1234567890',
                'nama_pemilik_rekening' => 'Pemilik APPKONKOS',
                'status_verifikasi' => 'terverifikasi',
            ]
        );

        $pencariUser = User::updateOrCreate(
            ['email' => 'pencari@appkonkos.test'],
            [
                'name' => 'Pencari APPKONKOS',
                'no_telepon' => '083333333333',
                'role' => 'pencari',
                'status_akun' => true,
                'email_verified_at' => now(),
                'password' => 'password',
            ]
        );

        PencariKos::updateOrCreate(
            ['user_id' => $pencariUser->id],
            [
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => Carbon::parse('2000-01-01'),
                'pekerjaan' => 'Mahasiswa',
                'nama_instansi' => 'Universitas Contoh',
                'kota_asal' => 'Bandar Lampung',
            ]
        );
    }
}
