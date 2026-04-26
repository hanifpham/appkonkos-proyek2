<?php

declare(strict_types=1);

namespace Tests\Feature\SuperAdmin;

use App\Livewire\SuperAdmin\PengaturanProfil;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PengaturanProfilTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_update_global_system_settings(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'email' => 'superadmin-settings@example.com',
        ]);

        $this->actingAs($superadmin);

        Livewire::test(PengaturanProfil::class)
            ->assertSet('komisiPlatform', '5')
            ->assertSet('potonganRefund', '25')
            ->set('activeTab', 'pengaturan_sistem')
            ->set('komisiPlatform', '7.5')
            ->set('potonganRefund', '30')
            ->call('simpanPengaturanSistem')
            ->assertDispatched('appkonkos-toast')
            ->assertSet('komisiPlatform', '7.5')
            ->assertSet('potonganRefund', '30');

        $this->assertSame('7.5', Setting::getValue(Setting::KEY_PLATFORM_COMMISSION));
        $this->assertSame('30', Setting::getValue(Setting::KEY_REFUND_DEDUCTION));
    }
}
