<?php

declare(strict_types=1);

namespace Tests\Feature\Mitra;

use App\Livewire\Mitra\Keuangan;
use App\Models\PemilikProperti;
use App\Models\PencairanDana;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class KeuanganTest extends TestCase
{
    use RefreshDatabase;

    public function test_mitra_can_see_rejected_withdrawal_reason_in_history(): void
    {
        $user = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'mitra-keuangan@example.com',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $user->id,
            'nama_bank' => 'BCA',
            'nomor_rekening' => '1234567890',
            'nama_pemilik_rekening' => 'Mitra Keuangan',
        ]);

        PencairanDana::create([
            'pemilik_properti_id' => $pemilik->id,
            'nominal' => 350000,
            'status' => 'ditolak',
            'catatan_admin' => 'Nama pemilik rekening tidak sesuai.',
        ]);

        $this->actingAs($user);

        Livewire::test(Keuangan::class)
            ->assertSee('#WD-00001')
            ->assertSee('Ditolak')
            ->assertSee('Alasan: Nama pemilik rekening tidak sesuai.');
    }
}
