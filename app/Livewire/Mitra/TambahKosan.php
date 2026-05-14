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

    public string $fasilitas_umum = '';

    /** @var mixed */
    public $foto_1;

    /** @var mixed */
    public $foto_2;

    /** @var mixed */
    public $foto_3;

    /** @var mixed */
    public $foto_4;

    public array $existingPhotoUrls = [];

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
        return [
            'nama_properti' => ['required', 'string', 'max:255'],
            'jenis_kos' => $this->tipe_properti === 'kosan'
                ? ['required', 'in:putra,putri,campur']
                : ['nullable', 'in:putra,putri,campur'],
            'alamat_lengkap' => ['required', 'string', 'max:2000'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'peraturan_kos' => ['required', 'string', 'max:5000'],
            'fasilitas_umum' => ['nullable', 'string', 'max:5000'],
            'foto_1' => $this->editId === null
                ? ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']
                : ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'foto_2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'foto_3' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'foto_4' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
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
            'fasilitas_umum' => 'Fasilitas Umum',
            'foto_1' => 'Foto Utama',
            'foto_2' => 'Foto Samping',
            'foto_3' => 'Foto Dalam',
            'foto_4' => 'Foto Fasilitas',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'jenis_kos.required' => 'Kategori Kos wajib dipilih untuk properti kosan.',
            'jenis_kos.in' => 'Kategori Kos yang dipilih tidak valid.',
            'string' => 'Kolom :attribute harus berupa teks.',
            'numeric' => 'Kolom :attribute harus berupa angka.',
            'image' => 'File harus berupa gambar.',
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
            'fasilitas_umum' => $this->fasilitas_umum,
        ];

        $uploadedPhotos = array_filter([
            $this->foto_1,
            $this->foto_2,
            $this->foto_3,
            $this->foto_4,
        ]);

        if ($this->editId !== null) {
            $kosan = $this->ownedKosan($this->editId);
            $kosan->update(array_merge($payload, $this->getResubmissionPayload($kosan->status)));

            if (!empty($uploadedPhotos)) {
                $kosan->clearMediaCollection('foto_properti');
                foreach ($uploadedPhotos as $foto) {
                    $kosan->addMedia($foto)->toMediaCollection('foto_properti');
                }
            }

            session()->flash('success', 'Profil kosan berhasil diperbarui. Kelola tipe kamar dan nomor kamar di langkah berikutnya.');
        } else {
            $kosan = Kosan::create($payload);
            foreach ($uploadedPhotos as $foto) {
                $kosan->addMedia($foto)->toMediaCollection('foto_properti');
            }

            session()->flash('success', 'Profil kosan berhasil disimpan. Lanjutkan dengan mengatur tipe kamar dan nomor kamar.');
        }

        return redirect()->route('mitra.properti.kelola-kamar', ['kosan_id' => $kosan->id]);
    }

    public function hapusFoto(int $slot): void
    {
        $property = "foto_" . $slot;
        if (property_exists($this, $property)) {
            $this->$property = null;
        }

        // If editing, also clear existing if it was matching this slot? 
        // Actually, the user just wants to clear the current selection.
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
        $this->peraturan_kos = $kosan->peraturan_kos ?? '';
        $this->fasilitas_umum = $kosan->fasilitas_umum ?? '';
        $this->existingPhotoUrls = $kosan->getMediaDisplayUrls('foto_properti');
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
