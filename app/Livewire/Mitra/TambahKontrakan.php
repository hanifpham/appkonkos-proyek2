<?php

declare(strict_types=1);

namespace App\Livewire\Mitra;

use App\Models\Kontrakan;
use App\Models\PemilikProperti;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class TambahKontrakan extends Component
{
    use WithFileUploads;

    public ?int $editId = null;

    public string $nama_properti = '';

    public string $alamat_lengkap = '';

    public string $latitude = '';

    public string $longitude = '';

    public string $harga_sewa_tahun = '';

    public string $fasilitas = '';

    public string $peraturan_kontrakan = '';

    public string $sisa_kamar = '1';

    public $foto_properti = null;

    public ?string $existingPhotoUrl = null;

    public function mount(): void
    {
        $editId = request()->query('edit');

        if (is_numeric($editId)) {
            $this->loadKontrakan((int) $editId);
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
            'alamat_lengkap' => ['required', 'string', 'max:2000'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'harga_sewa_tahun' => ['required', 'integer', 'min:0'],
            'fasilitas' => ['required', 'string', 'max:5000'],
            'peraturan_kontrakan' => ['required', 'string', 'max:5000'],
            'sisa_kamar' => ['required', 'integer', 'min:0'],
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
            'alamat_lengkap' => 'Alamat Lengkap',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'harga_sewa_tahun' => 'Harga Sewa per Tahun',
            'fasilitas' => 'Fasilitas',
            'peraturan_kontrakan' => 'Peraturan Kontrakan',
            'sisa_kamar' => 'Unit Tersedia',
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
            'integer' => 'Kolom :attribute harus berupa angka bulat.',
            'image' => 'Kolom :attribute harus berupa file gambar.',
            'mimes' => 'Format :attribute harus berupa: :values.',
            'max.string' => 'Teks :attribute maksimal :max karakter.',
            'max.file' => 'Ukuran :attribute maksimal :max kilobytes.',
            'between.numeric' => 'Nilai :attribute harus berada di antara :min sampai :max.',
            'min.numeric' => 'Nilai :attribute minimal :min.',
        ];
    }

    public function simpan()
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
            'alamat_lengkap' => $this->alamat_lengkap,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'harga_sewa_tahun' => (int) $this->harga_sewa_tahun,
            'fasilitas' => $this->fasilitas,
            'peraturan_kontrakan' => $this->peraturan_kontrakan,
            'sisa_kamar' => (int) $this->sisa_kamar,
        ];

        if ($this->editId !== null) {
            $kontrakan = $this->ownedKontrakan($this->editId);
            $kontrakan->update(array_merge($payload, $this->getResubmissionPayload($kontrakan->status)));

            if ($this->foto_properti !== null) {
                $kontrakan->clearMediaCollection('foto_properti');
                $kontrakan->addMedia($this->foto_properti)->toMediaCollection('foto_properti');
            }

            session()->flash('success', 'Data kontrakan berhasil diperbarui.');
        } else {
            $kontrakan = Kontrakan::create($payload);
            $kontrakan->addMedia($this->foto_properti)->toMediaCollection('foto_properti');

            session()->flash('success', 'Kontrakan baru berhasil ditambahkan.');
        }

        return redirect()->route('mitra.properti');
    }

    #[Layout('layouts.mitra.utama')]
    public function render(): View
    {
        return view('livewire.mitra.properti.form-kontrakan');
    }

    protected function loadKontrakan(int $kontrakanId): void
    {
        $kontrakan = $this->ownedKontrakan($kontrakanId);

        $this->editId = $kontrakan->id;
        $this->nama_properti = $kontrakan->nama_properti;
        $this->alamat_lengkap = $kontrakan->alamat_lengkap;
        $this->latitude = (string) $kontrakan->latitude;
        $this->longitude = (string) $kontrakan->longitude;
        $this->harga_sewa_tahun = (string) $kontrakan->harga_sewa_tahun;
        $this->fasilitas = $kontrakan->fasilitas;
        $this->peraturan_kontrakan = $kontrakan->peraturan_kontrakan;
        $this->sisa_kamar = (string) $kontrakan->sisa_kamar;
        $this->existingPhotoUrl = $kontrakan->getMediaDisplayUrl('foto_properti');
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

    protected function ownedKontrakan(int $kontrakanId): Kontrakan
    {
        return $this->pemilikProperti()
            ->kontrakan()
            ->findOrFail($kontrakanId);
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
