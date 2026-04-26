<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class PengaturanProfil extends Component
{
    use WithFileUploads;

    public string $activeTab = 'informasi_pribadi';

    public string $name = '';

    public string $email = '';

    public string $no_telepon = '';

    public string $profilePhotoUrl = '';

    public $foto_baru = null;

    public bool $notifEmail = true;

    public bool $notifPush = true;

    public bool $notifWhatsapp = false;

    public bool $notifRefund = true;

    public bool $notifModerasi = true;

    public bool $notifPencairan = false;

    public string $komisiPlatform = '5';

    public string $potonganRefund = '25';

    public function mount(): void
    {
        $this->loadProfileData();
        $this->loadSystemSettings();
    }

    #[Layout('layouts.mitra.utama')]
    public function render(): View
    {
        return view('livewire.superadmin.pengaturan-profil');
    }

    /**
     * @throws ValidationException
     */
    public function updatedFotoBaru(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user === null) {
            abort(403, 'Unauthorized action.');
        }

        $this->validate(['foto_baru' => 'image|max:2048']);

        $oldProfilePhotoPath = $user->profile_photo_path;
        $path = $this->foto_baru->store('profile-photos', 'public');
        $this->ensurePublicStorageFile($path);

        auth()->user()->update([
            'profile_photo_path' => $path,
        ]);

        $this->deleteStoredProfilePhoto($oldProfilePhotoPath, $path);

        $freshUser = $user->fresh();

        if (! $freshUser instanceof User) {
            abort(500, 'Gagal memuat ulang data pengguna.');
        }

        Auth::setUser($freshUser);

        $this->foto_baru = null;
        $this->loadProfileData();
        $this->resetValidation('foto_baru');

        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Foto profil diperbarui',
            text: 'Foto profil Super Admin berhasil diunggah.'
        );

        $this->dispatch('appkonkos-profile-photo-updated', url: $this->profilePhotoUrl);
    }

    /**
     * @throws ValidationException
     */
    public function simpanProfil(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user === null) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $validated = $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'no_telepon' => ['required', 'string', 'max:30'],
            ], [
                'name.required' => 'Nama lengkap wajib diisi.',
                'no_telepon.required' => 'Nomor handphone wajib diisi.',
                'no_telepon.max' => 'Nomor handphone maksimal 30 karakter.',
            ], [
                'name' => 'nama lengkap',
                'no_telepon' => 'nomor handphone',
            ]);
        } catch (ValidationException $exception) {
            $this->dispatchValidationErrorToast($exception);

            throw $exception;
        }

        $user->update([
            'name' => $validated['name'],
            'no_telepon' => $validated['no_telepon'],
        ]);

        $freshUser = $user->fresh();

        if ($freshUser instanceof User) {
            Auth::setUser($freshUser);
        }

        $this->loadProfileData();

        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Profil diperbarui',
            text: 'Informasi profil Super Admin berhasil disimpan.'
        );
    }

    public function hapusFoto(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user === null) {
            abort(403, 'Unauthorized action.');
        }

        $oldProfilePhotoPath = $user->profile_photo_path;

        $user->update([
            'profile_photo_path' => null,
        ]);

        $this->deleteStoredProfilePhoto($oldProfilePhotoPath, '');

        $freshUser = $user->fresh();

        if ($freshUser instanceof User) {
            Auth::setUser($freshUser);
        }

        $this->foto_baru = null;
        $this->loadProfileData();

        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Foto profil dihapus',
            text: 'Foto profil berhasil dikembalikan ke avatar default.'
        );

        $this->dispatch('appkonkos-profile-photo-updated', url: $this->profilePhotoUrl);
    }

    public function simpanNotifikasi(): void
    {
        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Preferensi disimpan',
            text: 'Preferensi notifikasi Super Admin berhasil disimpan untuk sesi ini.'
        );
    }

    /**
     * @throws ValidationException
     */
    public function simpanPengaturanSistem(): void
    {
        try {
            $validated = $this->validate([
                'komisiPlatform' => ['required', 'numeric', 'min:0', 'max:100'],
                'potonganRefund' => ['required', 'numeric', 'min:0', 'max:100'],
            ], [
                'komisiPlatform.required' => 'Komisi platform wajib diisi.',
                'komisiPlatform.numeric' => 'Komisi platform harus berupa angka.',
                'komisiPlatform.min' => 'Komisi platform minimal 0%.',
                'komisiPlatform.max' => 'Komisi platform maksimal 100%.',
                'potonganRefund.required' => 'Potongan refund wajib diisi.',
                'potonganRefund.numeric' => 'Potongan refund harus berupa angka.',
                'potonganRefund.min' => 'Potongan refund minimal 0%.',
                'potonganRefund.max' => 'Potongan refund maksimal 100%.',
            ], [
                'komisiPlatform' => 'komisi platform',
                'potonganRefund' => 'potongan refund',
            ]);
        } catch (ValidationException $exception) {
            $this->dispatchValidationErrorToast($exception);

            throw $exception;
        }

        Setting::putValue(Setting::KEY_PLATFORM_COMMISSION, $validated['komisiPlatform']);
        Setting::putValue(Setting::KEY_REFUND_DEDUCTION, $validated['potonganRefund']);

        $this->loadSystemSettings();

        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Pengaturan sistem disimpan',
            text: 'Komisi platform dan potongan refund berhasil diperbarui.'
        );
    }

    protected function loadProfileData(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user === null) {
            abort(403, 'Unauthorized action.');
        }

        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->no_telepon = $user->no_telepon ?? '';
        $this->profilePhotoUrl = $this->profilePhotoUrlFor($user);
    }

    protected function loadSystemSettings(): void
    {
        Setting::ensureDefaults();

        $this->komisiPlatform = Setting::getValue(Setting::KEY_PLATFORM_COMMISSION, '5');
        $this->potonganRefund = Setting::getValue(Setting::KEY_REFUND_DEDUCTION, '25');
    }

    protected function profilePhotoUrlFor(User $user): string
    {
        $timestamp = $user->updated_at?->timestamp ?? now()->timestamp;

        if (is_string($user->profile_photo_path) && $user->profile_photo_path !== '') {
            $this->ensurePublicStorageFile($user->profile_photo_path);

            $baseUrl = rtrim(request()->getBaseUrl(), '/');
            $storageUrl = ($baseUrl === '' ? '' : $baseUrl).'/storage/'.ltrim($user->profile_photo_path, '/');

            return $storageUrl.'?v='.$timestamp;
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($user->name ?? 'Super Admin').'&color=113C7A&background=EBF4FF';
    }

    protected function deleteStoredProfilePhoto(mixed $oldPath, string $newPath): void
    {
        if (! is_string($oldPath) || trim($oldPath) === '') {
            return;
        }

        $normalizedPath = ltrim($oldPath, '/');
        $normalizedPath = preg_replace('#^(storage/|public/storage/)#', '', $normalizedPath) ?? $normalizedPath;

        if ($normalizedPath === '' || $normalizedPath === $newPath) {
            return;
        }

        Storage::disk('public')->delete($normalizedPath);
        File::delete(public_path('storage/'.$normalizedPath));
    }

    protected function ensurePublicStorageFile(string $path): void
    {
        $normalizedPath = ltrim($path, '/');
        $normalizedPath = preg_replace('#^(storage/|public/storage/)#', '', $normalizedPath) ?? $normalizedPath;

        if ($normalizedPath === '') {
            return;
        }

        $sourcePath = Storage::disk('public')->path($normalizedPath);
        $publicPath = public_path('storage/'.$normalizedPath);

        if (! File::exists($sourcePath)) {
            return;
        }

        File::ensureDirectoryExists(dirname($publicPath));
        File::copy($sourcePath, $publicPath);
    }

    protected function dispatchValidationErrorToast(ValidationException $exception): void
    {
        $message = collect($exception->errors())
            ->flatten()
            ->first();

        $this->dispatch(
            'appkonkos-validasi-error',
            title: 'Validasi gagal',
            text: is_string($message) && $message !== ''
                ? $message
                : 'Ada data profil yang belum diisi dengan benar.'
        );
    }
}
