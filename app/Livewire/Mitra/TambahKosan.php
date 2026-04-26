<?php

declare(strict_types=1);

namespace App\Livewire\Mitra;

use App\Models\Kosan;
use App\Models\PemilikProperti;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class TambahKosan extends Component
{
    use WithFileUploads;

    public ?int $editId = null;

    public string $tipe_properti = 'kosan';

    public string $nama_properti = '';

    public string $jenis_kos = '';

    public string $alamat_lengkap = '';

    public string $latitude = '';

    public string $longitude = '';

    public string $peraturan_kos = '';

    public $foto_properti = null;

    public ?string $existingPhotoUrl = null;

    public function mount(): void
    {
        $editId = request()->query('edit');

        if (is_numeric($editId)) {
            $this->loadKosan((int) $editId);
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        $fotoRules = $this->editId === null
            ? ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']
            : ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'];

        return [
            'nama_properti' => ['required', 'string', 'max:255'],
            'jenis_kos' => $this->tipe_properti === 'kosan'
                ? ['required', 'in:putra,putri,campur']
                : ['nullable', 'in:putra,putri,campur'],
            'alamat_lengkap' => ['required', 'string', 'max:2000'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'peraturan_kos' => ['required', 'string', 'max:5000'],
            'foto_properti' => $fotoRules,
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'nama_properti' => 'Nama Properti',
            'jenis_kos' => 'Kategori Kos',
            'alamat_lengkap' => 'Alamat Lengkap',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'peraturan_kos' => 'Peraturan Kos',
            'foto_properti' => 'Foto Properti',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'string' => 'Kolom :attribute harus berupa teks.',
            'numeric' => 'Kolom :attribute harus berupa angka.',
            'image' => 'Kolom :attribute harus berupa file gambar.',
            'in' => 'Pilihan :attribute tidak valid.',
            'mimes' => 'Format :attribute harus berupa: :values.',
            'max.string' => 'Teks :attribute maksimal :max karakter.',
            'max.file' => 'Ukuran :attribute maksimal :max kilobytes.',
            'between.numeric' => 'Nilai :attribute harus berada di antara :min sampai :max.',
        ];
    }

    public function simpan(): Redirector|RedirectResponse
    {
        try {
            $this->validate();
        } catch (ValidationException $exception) {
            $this->dispatch('appkonkos-validasi-error');

            throw $exception;
        }

        $payload = [
            'pemilik_properti_id' => $this->pemilikProperti()->id,
            'nama_properti' => $this->nama_properti,
            'jenis_kos' => $this->jenis_kos,
            'alamat_lengkap' => $this->alamat_lengkap,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'peraturan_kos' => $this->peraturan_kos,
        ];

        if ($this->editId !== null) {
            $kosan = $this->ownedKosan($this->editId);
            $kosan->update(array_merge($payload, $this->getResubmissionPayload($kosan->status)));

            if ($this->foto_properti !== null) {
                $kosan->clearMediaCollection('foto_properti');
                $kosan->addMedia($this->foto_properti)->toMediaCollection('foto_properti');
            }

            session()->flash('success', 'Profil kosan berhasil diperbarui. Kelola tipe kamar dan nomor kamar di langkah berikutnya.');
        } else {
            $kosan = Kosan::create($payload);
            $kosan->addMedia($this->foto_properti)->toMediaCollection('foto_properti');

            session()->flash('success', 'Profil kosan berhasil disimpan. Lanjutkan dengan mengatur tipe kamar dan nomor kamar.');
        }

        return redirect()->route('mitra.properti.kelola-kamar', ['kosan_id' => $kosan->id]);
    }

    #[Layout('layouts.mitra.utama')]
    public function render(): View
    {
        return view('livewire.mitra.properti.form-kosan');
    }

    protected function loadKosan(int $kosanId): void
    {
        $kosan = $this->ownedKosan($kosanId);

        $this->editId = $kosan->id;
        $this->nama_properti = $kosan->nama_properti;
        $this->jenis_kos = $kosan->jenis_kos ?? '';
        $this->alamat_lengkap = $kosan->alamat_lengkap;
        $this->latitude = (string) $kosan->latitude;
        $this->longitude = (string) $kosan->longitude;
        $this->peraturan_kos = $kosan->peraturan_kos;
        $this->existingPhotoUrl = $kosan->getMediaDisplayUrl('foto_properti');
    }

    protected function pemilikProperti(): PemilikProperti
    {
        /** @var User|null $user */
        $user = Auth::user();
        $pemilikProperti = $user?->pemilikProperti()->first();

        if ($pemilikProperti === null) {
            abort(403, 'Unauthorized action.');
        }

        return $pemilikProperti;
    }

    protected function ownedKosan(int $kosanId): Kosan
    {
        return $this->pemilikProperti()
            ->kosan()
            ->findOrFail($kosanId);
    }

    /**
     * @return array<string, null|string>
     */
    protected function getResubmissionPayload(?string $status): array
    {
        if (! in_array($status, ['ditolak', 'suspend'], true)) {
            return [];
        }

        return [
            'status' => 'pending',
            'alasan_penolakan' => null,
        ];
    }
}
