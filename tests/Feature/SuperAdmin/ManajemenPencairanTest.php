<?php

declare(strict_types=1);

namespace Tests\Feature\SuperAdmin;

use App\Livewire\SuperAdmin\ManajemenPencairan;
use App\Models\PemilikProperti;
use App\Models\PencairanDana;
use App\Models\User;
use App\Notifications\AdminAlert;
use App\Notifications\PencairanStatusNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ManajemenPencairanTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_confirm_and_process_withdrawal_actions(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'email' => 'superadmin-pencairan@example.com',
        ]);

        $pemilikUser = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'pemilik-pencairan@example.com',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $pemilikUser->id,
            'nama_bank' => 'BCA',
            'nomor_rekening' => '1234509876',
        ]);

        $pencairanDisetujui = PencairanDana::create([
            'pemilik_properti_id' => $pemilik->id,
            'nominal' => 750000,
            'status' => 'pending',
            'catatan_admin' => 'Catatan lama',
        ]);

        $pencairanDitolak = PencairanDana::create([
            'pemilik_properti_id' => $pemilik->id,
            'nominal' => 550000,
            'status' => 'pending',
        ]);

        $this->actingAs($superadmin);

        Livewire::test(ManajemenPencairan::class)
            ->call('konfirmasiSetuju', $pencairanDisetujui->id)
            ->assertDispatched('swal:confirm-approve')
            ->call('prosesSetuju', $pencairanDisetujui->id)
            ->call('konfirmasiTolak', $pencairanDitolak->id)
            ->assertDispatched('swal:confirm-reject')
            ->call('prosesTolak', $pencairanDitolak->id, 'Data rekening tidak dapat diverifikasi.');

        $this->assertDatabaseHas('pencairan_dana', [
            'id' => $pencairanDisetujui->id,
            'status' => 'sukses',
            'catatan_admin' => null,
        ]);

        $this->assertDatabaseHas('pencairan_dana', [
            'id' => $pencairanDitolak->id,
            'status' => 'ditolak',
            'catatan_admin' => 'Data rekening tidak dapat diverifikasi.',
        ]);

        $this->assertDatabaseCount('notifications', 4);
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $pemilikUser->id,
            'notifiable_type' => User::class,
            'type' => PencairanStatusNotification::class,
        ]);
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $superadmin->id,
            'notifiable_type' => User::class,
            'type' => AdminAlert::class,
        ]);
    }
}
