<?php

declare(strict_types=1);

namespace App\Livewire\Mitra;

use App\Models\PemilikProperti;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class PengaturanProfil extends Component
{
    use WithFileUploads;

    public string $activeTab = 'informasi_pribadi';

    public string $nama_lengkap = '';

    public string $email = '';

    public string $no_telepon = '';

    public string $alamat_domisili = '';

    public string $nik_ktp = '';

    public string $nama_bank = '';

    public string $nomor_rekening = '';

    public string $nama_pemilik_rekening = '';

    public string $status_verifikasi = 'belum';

    public string $profilePhotoUrl = '';

    public $foto_baru = null;

    public $foto_ktp = null;

    public $foto_selfie = null;

    public bool $notif_whatsapp_pesanan_baru = true;

    public bool $notif_whatsapp_pembayaran_sukses = true;

    public bool $notif_whatsapp_ulasan_baru = true;

    public bool $notif_email_pesanan_baru = true;

    public bool $notif_email_pembayaran_sukses = true;

    public bool $notif_email_ulasan_baru = true;

    public bool $notif_aplikasi_pesanan_baru = true;

    public bool $notif_aplikasi_pembayaran_sukses = true;

    public bool $notif_aplikasi_ulasan_baru = true;

    public ?string $existingFotoKtpUrl = null;

    public ?string $existingFotoSelfieUrl = null;

    public function mount(): void
    {
        $this->loadProfileData();
    }

    #[Layout('layouts.mitra.utama')]
    public function render(): View
    {
        return view('livewire.mitra.pengaturan-profil', [
            'bankOptions' => $this->bankOptions(),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function updatedFotoBaru(): void
    {
        try {
            $this->validate([
                'foto_baru' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            ], [
                'foto_baru.required' => 'Foto profil wajib dipilih.',
                'foto_baru.image' => 'Foto profil harus berupa gambar.',
                'foto_baru.mimes' => 'Foto profil harus berformat JPG, JPEG, PNG, atau WEBP.',
                'foto_baru.max' => 'Ukuran foto profil maksimal 2MB.',
            ]);
        } catch (ValidationException $exception) {
            $this->dispatchValidationErrorToast($exception);

            throw $exception;
        }
    }

    /**
     * @throws ValidationException
     */
    public function simpanFotoProfil(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user === null) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $this->validate([
                'foto_baru' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            ], [
                'foto_baru.required' => 'Pilih foto profil terlebih dahulu.',
                'foto_baru.image' => 'Foto profil harus berupa gambar.',
                'foto_baru.mimes' => 'Foto profil harus berformat JPG, JPEG, PNG, atau WEBP.',
                'foto_baru.max' => 'Ukuran foto profil maksimal 2MB.',
            ]);
        } catch (ValidationException $exception) {
            $this->dispatchValidationErrorToast($exception);

            throw $exception;
        }

        $oldProfilePhotoPath = $user->profile_photo_path;
        $extension = $this->foto_baru->getClientOriginalExtension() ?: $this->foto_baru->extension() ?: 'jpg';
        $path = $this->foto_baru->storeAs(
            'profile-photos',
            'user-'.$user->id.'-'.now()->format('YmdHis').'.'.$extension,
            'public'
        );

        if (! is_string($path) || $path === '') {
            throw ValidationException::withMessages([
                'foto_baru' => 'Foto profil gagal disimpan. Silakan coba lagi.',
            ]);
        }

        $this->ensurePublicStorageFile($path);

        $user->update([
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
            text: 'Foto profil berhasil diunggah.'
        );

        $this->dispatch('swal:toast', [
            'icon' => 'success',
            'title' => 'Foto profil berhasil diperbarui!',
        ]);

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
            $validated = $this->validate($this->rules(), $this->messages(), $this->validationAttributes());
        } catch (ValidationException $exception) {
            $this->dispatchValidationErrorToast($exception);

            throw $exception;
        }

        $user->update([
            'name' => $validated['nama_lengkap'],
            'no_telepon' => (string) $validated['no_telepon'],
        ]);

        $existingPemilik = $user->pemilikProperti;
        $uploadedVerificationFiles = $this->foto_ktp !== null || $this->foto_selfie !== null;

        $pemilik = $user->pemilikProperti()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'alamat_domisili' => $this->nullableString($validated['alamat_domisili'] ?? null),
                'nik_ktp' => $this->nullableString($validated['nik_ktp'] ?? null),
                'nama_bank' => $this->nullableString($validated['nama_bank'] ?? null),
                'nomor_rekening' => $this->nullableString($validated['nomor_rekening'] ?? null),
                'nama_pemilik_rekening' => $this->nullableString($validated['nama_pemilik_rekening'] ?? null),
                'status_verifikasi' => $uploadedVerificationFiles
                    ? 'pending'
                    : ($existingPemilik?->status_verifikasi ?? 'belum'),
            ]
        );

        if ($this->foto_ktp !== null) {
            $pemilik->clearMediaCollection('foto_ktp');
            $pemilik
                ->addMedia($this->foto_ktp->getRealPath())
                ->usingFileName($this->foto_ktp->getClientOriginalName())
                ->toMediaCollection('foto_ktp');
        }

        if ($this->foto_selfie !== null) {
            $pemilik->clearMediaCollection('foto_selfie');
            $pemilik
                ->addMedia($this->foto_selfie->getRealPath())
                ->usingFileName($this->foto_selfie->getClientOriginalName())
                ->toMediaCollection('foto_selfie');
        }

        Auth::setUser($user->fresh());

        $this->foto_ktp = null;
        $this->foto_selfie = null;
        $this->loadProfileData();
        $this->resetValidation();

        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Profil diperbarui',
            text: 'Informasi profil dan data verifikasi berhasil disimpan.'
        );
    }

    public function resetForm(): void
    {
        $this->foto_baru = null;
        $this->foto_ktp = null;
        $this->foto_selfie = null;
        $this->resetValidation();
        $this->loadProfileData();
    }

    public function updated(string $property, mixed $value): void
    {
        if (! in_array($property, $this->notificationPreferenceFields(), true)) {
            return;
        }

        $pemilik = $this->persistNotificationPreferences();

        $this->{$property} = (bool) $pemilik->{$property};

        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Notifikasi diperbarui',
            text: 'Preferensi notifikasi berhasil disimpan.'
        );
    }

    public function isVerified(): bool
    {
        return $this->status_verifikasi === 'terverifikasi';
    }

    public function getStatusBadgeClasses(): string
    {
        return match ($this->status_verifikasi) {
            'pending' => 'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-100 dark:border-amber-800',
            'terverifikasi' => 'bg-green-50 text-green-600 dark:bg-green-900/30 dark:text-green-400 border border-green-100 dark:border-green-800',
            default => 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400 border border-red-100 dark:border-red-800',
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status_verifikasi) {
            'pending' => 'Pending',
            'terverifikasi' => 'Terverifikasi',
            'ditolak' => 'Ditolak',
            default => 'Belum',
        };
    }

    public function getVerificationIconClasses(): string
    {
        return match ($this->status_verifikasi) {
            'pending' => 'text-amber-500 bg-amber-50 dark:bg-amber-900/20',
            'terverifikasi' => 'text-green-500 bg-green-50 dark:bg-green-900/20',
            'ditolak' => 'text-red-500 bg-red-50 dark:bg-red-900/20',
            default => 'text-slate-500 bg-slate-100 dark:bg-slate-800',
        };
    }

    public function getMaskedNik(): string
    {
        $nik = preg_replace('/\D+/', '', $this->nik_ktp) ?? '';

        if ($nik === '') {
            return '-';
        }

        if (strlen($nik) <= 4) {
            return $nik;
        }

        return substr($nik, 0, 4).str_repeat('*', max(4, strlen($nik) - 4));
    }

    protected function loadProfileData(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user === null) {
            abort(403, 'Unauthorized action.');
        }

        $user->load('pemilikProperti.media');
        $pemilik = $user->pemilikProperti;

        $this->nama_lengkap = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->no_telepon = $user->no_telepon ?? '';
        $this->profilePhotoUrl = $this->profilePhotoUrlFor($user);
        $this->alamat_domisili = $pemilik?->alamat_domisili ?? '';
        $this->nik_ktp = $pemilik?->nik_ktp ?? '';
        $this->nama_bank = $pemilik?->nama_bank ?? '';
        $this->nomor_rekening = $pemilik?->nomor_rekening ?? '';
        $this->nama_pemilik_rekening = $pemilik?->nama_pemilik_rekening ?? '';
        $this->status_verifikasi = $pemilik?->status_verifikasi ?? 'belum';
        $this->notif_whatsapp_pesanan_baru = (bool) ($pemilik?->notif_whatsapp_pesanan_baru ?? true);
        $this->notif_whatsapp_pembayaran_sukses = (bool) ($pemilik?->notif_whatsapp_pembayaran_sukses ?? true);
        $this->notif_whatsapp_ulasan_baru = (bool) ($pemilik?->notif_whatsapp_ulasan_baru ?? true);
        $this->notif_email_pesanan_baru = (bool) ($pemilik?->notif_email_pesanan_baru ?? true);
        $this->notif_email_pembayaran_sukses = (bool) ($pemilik?->notif_email_pembayaran_sukses ?? true);
        $this->notif_email_ulasan_baru = (bool) ($pemilik?->notif_email_ulasan_baru ?? true);
        $this->notif_aplikasi_pesanan_baru = (bool) ($pemilik?->notif_aplikasi_pesanan_baru ?? true);
        $this->notif_aplikasi_pembayaran_sukses = (bool) ($pemilik?->notif_aplikasi_pembayaran_sukses ?? true);
        $this->notif_aplikasi_ulasan_baru = (bool) ($pemilik?->notif_aplikasi_ulasan_baru ?? true);
        $this->existingFotoKtpUrl = $pemilik?->getMediaDisplayUrl('foto_ktp') ?: null;
        $this->existingFotoSelfieUrl = $pemilik?->getMediaDisplayUrl('foto_selfie') ?: null;
    }

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'no_telepon' => ['required', 'numeric'],
            'alamat_domisili' => ['nullable', 'string', 'max:5000'],
            'nik_ktp' => ['nullable', 'string', 'max:32'],
            'nama_bank' => ['nullable', 'string', 'max:100'],
            'nomor_rekening' => ['nullable', 'string', 'max:30'],
            'nama_pemilik_rekening' => ['nullable', 'string', 'max:255'],
            'foto_ktp' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'foto_selfie' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'no_telepon.required' => 'Nomor WhatsApp wajib diisi.',
            'no_telepon.numeric' => 'Nomor WhatsApp harus berupa angka.',
            'foto_ktp.image' => 'Foto KTP harus berupa file gambar.',
            'foto_ktp.mimes' => 'Foto KTP harus berformat JPG, JPEG, PNG, atau WEBP.',
            'foto_ktp.max' => 'Ukuran Foto KTP maksimal 2MB.',
            'foto_selfie.image' => 'Foto selfie harus berupa file gambar.',
            'foto_selfie.mimes' => 'Foto selfie harus berformat JPG, JPEG, PNG, atau WEBP.',
            'foto_selfie.max' => 'Ukuran Foto selfie maksimal 2MB.',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'nama_lengkap' => 'nama lengkap',
            'no_telepon' => 'nomor WhatsApp',
            'alamat_domisili' => 'alamat domisili',
            'nik_ktp' => 'nomor NIK',
            'nama_bank' => 'nama bank',
            'nomor_rekening' => 'nomor rekening',
            'nama_pemilik_rekening' => 'nama pemilik rekening',
            'foto_ktp' => 'foto KTP',
            'foto_selfie' => 'foto selfie',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function bankOptions(): array
    {
        $options = [
            'BCA' => 'BCA (Bank Central Asia)',
            'Mandiri' => 'Mandiri',
            'BNI' => 'BNI (Bank Negara Indonesia)',
            'BRI' => 'BRI (Bank Rakyat Indonesia)',
            'BTN' => 'BTN (Bank Tabungan Negara)',
            'BSI' => 'BSI (Bank Syariah Indonesia)',
            'CIMB Niaga' => 'Bank CIMB Niaga',
            'Permata' => 'Bank Permata',
            'Danamon' => 'Bank Danamon',
            'Jago / Artos' => 'Bank Jago / Artos',
            'BJB' => 'BJB (Bank Jawa Barat)',
            'Lainnya' => 'Lainnya',
        ];

        $namaBank = trim($this->nama_bank);

        if ($namaBank !== '' && ! array_key_exists($namaBank, $options)) {
            $options = [$namaBank => $namaBank] + $options;
        }

        return $options;
    }

    protected function persistNotificationPreferences(): PemilikProperti
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user === null) {
            abort(403, 'Unauthorized action.');
        }

        return $user->pemilikProperti()->updateOrCreate(
            ['user_id' => $user->id],
            $this->notificationPreferencePayload()
        );
    }

    /**
     * @return array<string, bool>
     */
    protected function notificationPreferencePayload(): array
    {
        return [
            'notif_whatsapp_pesanan_baru' => $this->notif_whatsapp_pesanan_baru,
            'notif_whatsapp_pembayaran_sukses' => $this->notif_whatsapp_pembayaran_sukses,
            'notif_whatsapp_ulasan_baru' => $this->notif_whatsapp_ulasan_baru,
            'notif_email_pesanan_baru' => $this->notif_email_pesanan_baru,
            'notif_email_pembayaran_sukses' => $this->notif_email_pembayaran_sukses,
            'notif_email_ulasan_baru' => $this->notif_email_ulasan_baru,
            'notif_aplikasi_pesanan_baru' => $this->notif_aplikasi_pesanan_baru,
            'notif_aplikasi_pembayaran_sukses' => $this->notif_aplikasi_pembayaran_sukses,
            'notif_aplikasi_ulasan_baru' => $this->notif_aplikasi_ulasan_baru,
        ];
    }

    /**
     * @return list<string>
     */
    protected function notificationPreferenceFields(): array
    {
        return array_keys($this->notificationPreferencePayload());
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

        return 'https://ui-avatars.com/api/?name='.urlencode($user->name ?? 'User').'&color=113C7A&background=EBF4FF';
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

        if (! File::exists($publicPath) || File::size($publicPath) !== File::size($sourcePath)) {
            File::copy($sourcePath, $publicPath);
        }
    }

    protected function nullableString(mixed $value): ?string
    {
        $stringValue = trim((string) ($value ?? ''));

        return $stringValue !== '' ? $stringValue : null;
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
