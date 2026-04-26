<?php

declare(strict_types=1);

namespace Tests\Feature\Mitra;

use App\Models\Kosan;
use App\Models\PemilikProperti;
use App\Models\User;
use App\Notifications\PropertiDitolakNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationHeaderTest extends TestCase
{
    use RefreshDatabase;

    public function test_mitra_header_displays_unread_database_notifications(): void
    {
        $user = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'mitra-notification-header@example.com',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $user->id,
            'nama_bank' => 'Bank Test',
            'nomor_rekening' => '5656565656',
        ]);

        $kosan = Kosan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kos Notifikasi',
            'alamat_lengkap' => 'Jl. Notifikasi No. 1',
            'latitude' => -6.2,
            'longitude' => 106.8,
            'jenis_kos' => 'campur',
            'peraturan_kos' => 'Lengkap',
            'status' => 'ditolak',
        ]);

        $user->notify(new PropertiDitolakNotification($kosan, 'Dokumen pendukung belum lengkap.'));

        $this->actingAs($user);

        $this->get(route('mitra.properti'))
            ->assertOk()
            ->assertSee('1 pemberitahuan belum dibaca')
            ->assertSee('Properti Ditolak')
            ->assertSee('Dokumen pendukung belum lengkap.')
            ->assertSee('Edit Properti');
    }
}
