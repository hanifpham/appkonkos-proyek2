<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicHomeAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_is_publicly_accessible_without_login(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(config('app.name'))
            ->assertSee('Jelajah tanpa login')
            ->assertSee('Cari kos atau kontrakan dulu, login hanya saat mau pesan.')
            ->assertDontSee('Laravel has an incredibly rich ecosystem');
    }

    public function test_pencari_dashboard_still_requires_authentication(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_still_open_public_homepage(): void
    {
        $user = User::factory()->create([
            'role' => 'pencari',
        ]);

        $this->actingAs($user);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee(config('app.name'));
    }
}
