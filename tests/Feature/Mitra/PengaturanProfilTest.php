<?php

declare(strict_types=1);

namespace Tests\Feature\Mitra;

use App\Livewire\Mitra\PengaturanProfil;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class PengaturanProfilTest extends TestCase
{
    use RefreshDatabase;

    public function test_mitra_can_upload_profile_photo_and_persist_path(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'pemilik',
            'name' => 'Mitra Test',
        ]);

        $this->actingAs($user);

        Livewire::test(PengaturanProfil::class)
            ->set('foto_baru', UploadedFile::fake()->image('avatar.jpg'))
            ->assertHasNoErrors();

        $user->refresh();

        $this->assertNotNull($user->profile_photo_path);
        Storage::disk('public')->assertExists($user->profile_photo_path);
        $this->assertStringStartsWith('profile-photos/', $user->profile_photo_path);
        $this->assertTrue(File::exists(public_path('storage/'.$user->profile_photo_path)));
    }

    public function test_replacing_profile_photo_deletes_old_file_and_updates_user_record(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'pemilik',
            'name' => 'Mitra Test',
        ]);

        $this->actingAs($user);

        Livewire::test(PengaturanProfil::class)
            ->set('foto_baru', UploadedFile::fake()->image('avatar-old.jpg'))
            ->assertHasNoErrors();

        $user->refresh();
        $oldPath = $user->profile_photo_path;

        $this->assertNotNull($oldPath);
        Storage::disk('public')->assertExists($oldPath);

        Livewire::test(PengaturanProfil::class)
            ->set('foto_baru', UploadedFile::fake()->image('avatar-new.jpg'))
            ->assertHasNoErrors();

        $user->refresh();

        $this->assertNotNull($user->profile_photo_path);
        $this->assertNotSame($oldPath, $user->profile_photo_path);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($user->profile_photo_path);
        $this->assertFalse(File::exists(public_path('storage/'.$oldPath)));
        $this->assertTrue(File::exists(public_path('storage/'.$user->profile_photo_path)));
    }
}
