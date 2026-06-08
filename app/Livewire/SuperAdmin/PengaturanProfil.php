<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
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

    /** @var mixed */
    public $foto_baru = null;

    public bool $notifEmail = true;

    public bool $notifPush = true;

    public bool $notifWhatsapp = false;

    public bool $notifRefund = true;

    public bool $notifModerasi = true;

    public bool $notifPencairan = false;

    public string $komisiPlatform = '5';

    public string $potonganRefund = '25';

    public string $biayaLayanan = '10000';

    public string $minimumPencairan = '100000';

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

        $extension = $this->foto_baru->extension() ?: 'jpg';
        $user->clearMediaCollection('foto_profil');
        $user->addMedia($this->foto_baru->getRealPath())
            ->usingFileName('superadmin-' . $user->id . '-' . now()->format('YmdHis') . '.' . $extension)
            ->toMediaCollection('foto_profil');

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

        $user->clearMediaCollection('foto_profil');

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
                'biayaLayanan' => ['required', 'numeric', 'min:0'],
                'minimumPencairan' => ['required', 'numeric', 'min:0'],
            ], [
                'komisiPlatform.required' => 'Komisi platform wajib diisi.',
                'komisiPlatform.numeric' => 'Komisi platform harus berupa angka.',
                'komisiPlatform.min' => 'Komisi platform minimal 0%.',
                'komisiPlatform.max' => 'Komisi platform maksimal 100%.',
                'potonganRefund.required' => 'Potongan refund wajib diisi.',
                'potonganRefund.numeric' => 'Potongan refund harus berupa angka.',
                'potonganRefund.min' => 'Potongan refund minimal 0%.',
                'potonganRefund.max' => 'Potongan refund maksimal 100%.',
                'biayaLayanan.required' => 'Biaya layanan wajib diisi.',
                'biayaLayanan.numeric' => 'Biaya layanan harus berupa angka.',
                'biayaLayanan.min' => 'Biaya layanan minimal 0.',
                'minimumPencairan.required' => 'Minimum pencairan wajib diisi.',
                'minimumPencairan.numeric' => 'Minimum pencairan harus berupa angka.',
                'minimumPencairan.min' => 'Minimum pencairan minimal 0.',
            ], [
                'komisiPlatform' => 'komisi platform',
                'potonganRefund' => 'potongan refund',
                'biayaLayanan' => 'biaya layanan',
                'minimumPencairan' => 'minimum pencairan',
            ]);
        } catch (ValidationException $exception) {
            $this->dispatchValidationErrorToast($exception);

            throw $exception;
        }

        Setting::putValue(Setting::KEY_PLATFORM_COMMISSION, $validated['komisiPlatform']);
        Setting::putValue(Setting::KEY_REFUND_DEDUCTION, $validated['potonganRefund']);
        Setting::putValue(Setting::KEY_SERVICE_FEE, $validated['biayaLayanan']);
        Setting::putValue(Setting::KEY_MINIMUM_WITHDRAWAL, $validated['minimumPencairan']);

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
        $this->biayaLayanan = Setting::getValue(Setting::KEY_SERVICE_FEE, '10000');
        $this->minimumPencairan = Setting::getValue(Setting::KEY_MINIMUM_WITHDRAWAL, '100000');
    }

    protected function profilePhotoUrlFor(User $user): string
    {
        $timestamp = $user->updated_at?->timestamp ?? now()->timestamp;
        $mediaUrl = $user->getFirstMediaUrl('foto_profil');

        if ($mediaUrl !== '') {
            return $mediaUrl . '?v=' . $timestamp;
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'Super Admin') . '&color=113C7A&background=EBF4FF';
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
