<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Kontrakan;
use App\Models\Kosan;
use App\Models\PemilikProperti;
use App\Models\TipeKamar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class MediaDisplayUrlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        File::deleteDirectory(public_path('storage/media'));
    }

    public function test_media_display_url_uses_current_application_root_for_kosan_and_related_models(): void
    {
        Storage::fake('public');
        URL::forceRootUrl('http://localhost/appkonkos/public');

        $user = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'media-url@example.com',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $user->id,
            'nama_bank' => 'Bank Test',
            'no_rekening' => '1234567890',
        ]);

        $kosan = Kosan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kos Sakura',
            'alamat_lengkap' => 'Jl. Sakura No. 1',
            'latitude' => -6.1,
            'longitude' => 106.8,
            'peraturan_kos' => 'Tenang',
        ]);

        $kontrakan = Kontrakan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kontrakan Lily',
            'alamat_lengkap' => 'Jl. Lily No. 2',
            'latitude' => -6.2,
            'longitude' => 106.9,
            'harga_sewa_tahun' => 10000000,
            'fasilitas' => 'Garasi',
            'peraturan_kontrakan' => 'Jaga kebersihan',
            'sisa_kamar' => 1,
        ]);

        $tipeKamar = TipeKamar::create([
            'kosan_id' => $kosan->id,
            'nama_tipe' => 'Tipe AC',
            'harga_per_bulan' => 900000,
            'fasilitas_tipe' => 'AC',
        ]);

        $kosan->addMedia(UploadedFile::fake()->image('kosan.png'))->toMediaCollection('foto_properti');
        $kontrakan->addMedia(UploadedFile::fake()->image('kontrakan.png'))->toMediaCollection('foto_properti');
        $tipeKamar->addMedia(UploadedFile::fake()->image('interior.png'))->toMediaCollection('foto_interior');

        $kosanUrl = $kosan->getMediaDisplayUrl('foto_properti');
        $kontrakanUrl = $kontrakan->getMediaDisplayUrl('foto_properti');
        $tipeKamarUrl = $tipeKamar->getMediaDisplayUrl('foto_interior');

        $this->assertStringStartsWith('http://localhost/appkonkos/public/storage/', $kosanUrl);
        $this->assertStringStartsWith('http://localhost/appkonkos/public/storage/', $kontrakanUrl);
        $this->assertStringStartsWith('http://localhost/appkonkos/public/storage/', $tipeKamarUrl);
        $this->assertStringContainsString('/media/kosan/'.$kosan->id.'/foto-properti/', $kosanUrl);
        $this->assertStringContainsString('/media/kontrakan/'.$kontrakan->id.'/foto-properti/', $kontrakanUrl);
        $this->assertStringContainsString('/media/tipe-kamar/'.$tipeKamar->id.'/foto-interior/', $tipeKamarUrl);
    }

    public function test_public_storage_mirror_is_removed_when_property_media_is_cleared(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'pemilik',
            'email' => 'media-cleanup@example.com',
        ]);

        $pemilik = PemilikProperti::create([
            'user_id' => $user->id,
            'nama_bank' => 'Bank Test',
            'no_rekening' => '1234567890',
        ]);

        $kosan = Kosan::create([
            'pemilik_properti_id' => $pemilik->id,
            'nama_properti' => 'Kos Cleanup',
            'alamat_lengkap' => 'Jl. Bersih No. 1',
            'latitude' => -6.1,
            'longitude' => 106.8,
            'peraturan_kos' => 'Tenang',
        ]);

        $kosan->addMedia(UploadedFile::fake()->image('cleanup.png'))->toMediaCollection('foto_properti');

        $url = $kosan->getMediaDisplayUrl('foto_properti');
        $publicFilePath = $this->publicStoragePathFromUrl($url);
        $collectionDirectory = public_path('storage/media/kosan/'.$kosan->id.'/foto-properti');

        $this->assertTrue(File::exists($publicFilePath));

        $kosan->clearMediaCollection('foto_properti');

        $this->assertFalse(File::exists($publicFilePath));
        $this->assertFalse(File::exists($collectionDirectory));
    }

    private function publicStoragePathFromUrl(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);
        $relativePath = preg_replace('#^.*?/storage/#', '', (string) $path) ?? '';

        return public_path('storage/'.$relativePath);
    }
}
