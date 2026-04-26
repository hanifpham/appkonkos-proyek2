<?php

declare(strict_types=1);

namespace Tests\Feature\SuperAdmin;

use App\Livewire\SuperAdmin\ModerasiProperti;
use App\Models\Kamar;
use App\Models\Kontrakan;
use App\Models\Kosan;
use App\Models\PemilikProperti;
use App\Models\TipeKamar;
use App\Models\User;
use App\Notifications\AdminAlert;
use App\Notifications\PropertiDisetujuiNotification;
use App\Notifications\PropertiDitolakNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ModerasiPropertiTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_filter_moderation_list_and_open_detail(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'email' => 'superadmin-moderasi@example.com',
        ]);

        $pemilikUser = User::factory()->create([
            'role' => 'pemilik',
            'name' => 'Pemilik Mawar',
            'email' => 'pemilik-mawar@example.com',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $pemilikUser->id,
            'nama_bank' => 'Bank Test',
            'nomor_rekening' => '123456789',
        ]);

        $kosan = Kosan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kos Mawar',
            'alamat_lengkap' => 'Jl. Mawar No. 1',
            'latitude' => -6.4,
            'longitude' => 108.2,
            'jenis_kos' => 'putri',
            'peraturan_kos' => 'Wajib tertib',
            'status' => 'pending',
        ]);

        $tipeKamar = TipeKamar::create([
            'kosan_id' => $kosan->id,
            'nama_tipe' => 'Tipe Deluxe',
            'harga_per_bulan' => 950000,
            'fasilitas_tipe' => 'AC, kasur, lemari',
        ]);

        Kamar::create([
            'tipe_kamar_id' => $tipeKamar->id,
            'nomor_kamar' => 'A1',
            'status_kamar' => 'tersedia',
        ]);

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $superadmin->id,
            'notifiable_type' => User::class,
            'type' => AdminAlert::class,
        ]);

        $kontrakan = Kontrakan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kontrakan Melati',
            'alamat_lengkap' => 'Jl. Melati No. 2',
            'latitude' => -6.41,
            'longitude' => 108.21,
            'harga_sewa_tahun' => 12000000,
            'fasilitas' => 'Garasi',
            'peraturan_kontrakan' => 'Tidak boleh renovasi',
            'sisa_kamar' => 1,
            'status' => 'aktif',
        ]);

        $this->actingAs($superadmin);

        Livewire::test(ModerasiProperti::class)
            ->assertSee('Kos Mawar')
            ->assertSee('Kontrakan Melati')
            ->assertSee('PROP-'.str_pad((string) $kosan->id, 4, '0', STR_PAD_LEFT))
            ->set('filterTipe', 'kosan')
            ->assertSee('Kos Mawar')
            ->assertDontSee('Kontrakan Melati')
            ->set('filterTipe', 'kontrakan')
            ->assertDontSee('Kos Mawar')
            ->assertSee('Kontrakan Melati')
            ->set('filterTipe', '')
            ->set('filterStatus', 'pending')
            ->assertSee('Kos Mawar')
            ->assertDontSee('Kontrakan Melati')
            ->set('filterStatus', 'aktif')
            ->assertDontSee('Kos Mawar')
            ->assertSee('Kontrakan Melati');

        $this->get(route('superadmin.moderasi.detail', ['tipe' => 'kontrakan', 'id' => $kontrakan->id]))
            ->assertOk()
            ->assertSee('Detail Properti')
            ->assertSee('Kontrakan Melati')
            ->assertSee('Fasilitas Kontrakan')
            ->assertSee('Garasi')
            ->assertSee('Tidak boleh renovasi');

        $this->get(route('superadmin.moderasi.detail', ['tipe' => 'kosan', 'id' => $kosan->id]))
            ->assertOk()
            ->assertSee('Kos Mawar')
            ->assertSee('Peraturan Kos')
            ->assertSee('Wajib tertib')
            ->assertSee('Tipe Deluxe')
            ->assertSee('AC, kasur, lemari')
            ->assertSee('A1');
    }

    public function test_superadmin_can_reject_pending_property_and_takedown_active_property(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'email' => 'superadmin-properti-action@example.com',
        ]);

        $pemilikUser = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'pemilik-properti-action@example.com',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $pemilikUser->id,
            'nama_bank' => 'Bank Test',
            'nomor_rekening' => '987654321',
        ]);

        $kosan = Kosan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kos Kenanga',
            'alamat_lengkap' => 'Jl. Kenanga No. 10',
            'latitude' => -6.42,
            'longitude' => 108.22,
            'jenis_kos' => 'campur',
            'peraturan_kos' => 'Jaga kebersihan',
            'status' => 'pending',
        ]);

        $kontrakan = Kontrakan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kontrakan Cempaka',
            'alamat_lengkap' => 'Jl. Cempaka No. 11',
            'latitude' => -6.43,
            'longitude' => 108.23,
            'harga_sewa_tahun' => 15000000,
            'fasilitas' => 'Carport',
            'peraturan_kontrakan' => 'Tidak boleh sublet',
            'sisa_kamar' => 1,
            'status' => 'aktif',
        ]);

        $this->actingAs($superadmin);

        Livewire::test(ModerasiProperti::class)
            ->call('konfirmasiTolak', 'kosan', $kosan->id)
            ->assertDispatched('swal:confirm-reject')
            ->call('prosesTolak', 'kosan', $kosan->id, 'Foto properti tidak sesuai kondisi terbaru.')
            ->call('konfirmasiTakedown', 'kontrakan', $kontrakan->id)
            ->assertDispatched('swal:confirm-approve')
            ->call('takedownProperti', 'kontrakan', $kontrakan->id);

        $this->assertDatabaseHas('kosan', [
            'id' => $kosan->id,
            'status' => 'ditolak',
            'alasan_penolakan' => 'Foto properti tidak sesuai kondisi terbaru.',
        ]);

        $this->assertDatabaseHas('kontrakan', [
            'id' => $kontrakan->id,
            'status' => 'ditolak',
            'alasan_penolakan' => 'Properti ditakedown oleh super admin.',
        ]);

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $pemilikUser->id,
            'notifiable_type' => User::class,
            'type' => PropertiDitolakNotification::class,
        ]);
    }

    public function test_superadmin_can_approve_property_and_send_notification_to_owner(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'email' => 'superadmin-properti-approve@example.com',
        ]);

        $pemilikUser = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'pemilik-properti-approve@example.com',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $pemilikUser->id,
            'nama_bank' => 'Bank Test',
            'nomor_rekening' => '1212121212',
        ]);

        $kosan = Kosan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kos Disetujui',
            'alamat_lengkap' => 'Jl. Persetujuan No. 1',
            'latitude' => -6.45,
            'longitude' => 108.25,
            'jenis_kos' => 'putra',
            'peraturan_kos' => 'Tertib',
            'status' => 'pending',
        ]);

        $this->actingAs($superadmin);

        Livewire::test(ModerasiProperti::class)
            ->call('verifikasiProperti', 'kosan', $kosan->id);

        $this->assertDatabaseHas('kosan', [
            'id' => $kosan->id,
            'status' => 'aktif',
        ]);

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $pemilikUser->id,
            'notifiable_type' => User::class,
            'type' => PropertiDisetujuiNotification::class,
        ]);
    }
}
