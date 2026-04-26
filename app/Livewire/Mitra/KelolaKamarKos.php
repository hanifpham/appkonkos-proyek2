<?php

declare(strict_types=1);

namespace App\Livewire\Mitra;

use App\Models\Kamar;
use App\Models\Kosan;
use App\Models\PemilikProperti;
use App\Models\TipeKamar;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class KelolaKamarKos extends Component
{
    use WithFileUploads;

    public int $kosanId;

    public string $nama_tipe = '';

    public string $harga_per_bulan = '';

    public string $fasilitas_tipe = '';

    public $foto_interior = null;

    /**
     * @var array<int, list<string>>
     */
    public array $roomInputs = [];

    public function mount(int $kosan_id): void
    {
        $this->kosanId = $kosan_id;

        $this->ensureRoomInputs($this->currentKosan());
    }

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return [
            'nama_tipe' => ['required', 'string', 'max:255'],
            'harga_per_bulan' => ['required', 'integer', 'min:0'],
            'fasilitas_tipe' => ['required', 'string', 'max:5000'],
            'foto_interior' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'nama_tipe' => 'Nama Tipe',
            'harga_per_bulan' => 'Harga per Bulan',
            'fasilitas_tipe' => 'Fasilitas Tipe',
            'foto_interior' => 'Foto Interior',
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
            'integer' => 'Kolom :attribute harus berupa angka bulat.',
            'image' => 'Kolom :attribute harus berupa file gambar.',
            'mimes' => 'Format :attribute harus berupa: :values.',
            'max.string' => 'Teks :attribute maksimal :max karakter.',
            'max.file' => 'Ukuran :attribute maksimal :max kilobytes.',
            'min.numeric' => 'Nilai :attribute minimal :min.',
        ];
    }

    public function tambahTipeKamar(): void
    {
        $this->resetErrorBag();

        try {
            $this->validate();
        } catch (ValidationException $exception) {
            $this->dispatch('appkonkos-validasi-error');

            throw $exception;
        }

        $tipeKamar = $this->currentKosan()->tipeKamar()->create([
            'nama_tipe' => $this->nama_tipe,
            'harga_per_bulan' => (int) $this->harga_per_bulan,
            'fasilitas_tipe' => $this->fasilitas_tipe,
        ]);

        if ($this->foto_interior !== null) {
            $tipeKamar->addMedia($this->foto_interior)->toMediaCollection('foto_interior');
        }

        $this->reset(['nama_tipe', 'harga_per_bulan', 'fasilitas_tipe', 'foto_interior']);
        $this->roomInputs[$tipeKamar->id] = [''];

        session()->flash('success', 'Tipe kamar berhasil ditambahkan. Lanjutkan dengan mengisi nomor kamar spesifik.');
    }

    public function tambahInputKamar(int $tipeKamarId): void
    {
        $this->ownedTipeKamar($tipeKamarId);

        $this->roomInputs[$tipeKamarId] ??= [''];
        $this->roomInputs[$tipeKamarId][] = '';
    }

    public function hapusInputKamar(int $tipeKamarId, int $index): void
    {
        $this->ownedTipeKamar($tipeKamarId);

        if (! isset($this->roomInputs[$tipeKamarId][$index])) {
            return;
        }

        unset($this->roomInputs[$tipeKamarId][$index]);
        $this->roomInputs[$tipeKamarId] = array_values($this->roomInputs[$tipeKamarId]);

        if ($this->roomInputs[$tipeKamarId] === []) {
            $this->roomInputs[$tipeKamarId] = [''];
        }
    }

    public function simpanKamar(int $tipeKamarId): void
    {
        $this->resetErrorBag();

        $tipeKamar = $this->ownedTipeKamar($tipeKamarId);
        $nomorKamar = $this->sanitizeRoomInputs($tipeKamarId);

        try {
            Validator::make(
                ['nomor_kamar' => $nomorKamar->all()],
                [
                    'nomor_kamar' => ['required', 'array', 'min:1'],
                    'nomor_kamar.*' => ['required', 'string', 'max:255'],
                ],
                [
                    'required' => 'Kolom :attribute wajib diisi.',
                    'array' => 'Kolom :attribute harus berupa daftar data.',
                    'min.array' => 'Kolom :attribute minimal berisi :min item.',
                    'string' => 'Kolom :attribute harus berupa teks.',
                    'max.string' => 'Teks :attribute maksimal :max karakter.',
                ],
                [
                    'nomor_kamar' => 'Nomor Kamar',
                    'nomor_kamar.*' => 'Nomor Kamar',
                ]
            )->validate();
        } catch (ValidationException $exception) {
            $this->dispatch('appkonkos-validasi-error');

            throw $exception;
        }

        $normalizedInput = $nomorKamar
            ->map(static fn (string $nomor): string => mb_strtolower($nomor));

        if ($normalizedInput->unique()->count() !== $normalizedInput->count()) {
            $this->dispatch('appkonkos-validasi-error');

            throw ValidationException::withMessages([
                'roomInputs.'.$tipeKamarId => 'Nomor kamar tidak boleh duplikat dalam satu penyimpanan.',
            ]);
        }

        $existingNumbers = Kamar::query()
            ->select('nomor_kamar')
            ->whereHas('tipeKamar', function (Builder $query): void {
                $query->where('kosan_id', $this->kosanId);
            })
            ->get()
            ->map(static fn (Kamar $kamar): string => mb_strtolower($kamar->nomor_kamar));

        if ($normalizedInput->intersect($existingNumbers)->isNotEmpty()) {
            $this->dispatch('appkonkos-validasi-error');

            throw ValidationException::withMessages([
                'roomInputs.'.$tipeKamarId => 'Nomor kamar sudah digunakan di kosan ini.',
            ]);
        }

        foreach ($nomorKamar as $nomor) {
            $tipeKamar->kamar()->create([
                'nomor_kamar' => $nomor,
                'status_kamar' => 'tersedia',
            ]);
        }

        $this->roomInputs[$tipeKamarId] = [''];

        session()->flash('success', 'Nomor kamar berhasil disimpan.');
    }

    public function hapusKamar(int $kamarId): void
    {
        $this->resetErrorBag();

        $kamar = $this->ownedKamar($kamarId);

        if ($kamar->bookings()->exists()) {
            $this->addError('general', 'Kamar yang sudah terhubung ke booking tidak dapat dihapus.');

            return;
        }

        $kamar->delete();

        session()->flash('success', 'Kamar berhasil dihapus.');
    }

    public function hapusTipeKamar(int $tipeKamarId): void
    {
        $this->resetErrorBag();

        $tipeKamar = $this->ownedTipeKamar($tipeKamarId)->load(['kamar.bookings', 'media']);

        $isUsedByBooking = $tipeKamar->kamar->contains(
            static fn (Kamar $kamar): bool => $kamar->bookings->isNotEmpty()
        );

        if ($isUsedByBooking) {
            $this->addError('general', 'Tipe kamar yang sudah memiliki booking aktif tidak dapat dihapus.');

            return;
        }

        foreach ($tipeKamar->kamar as $kamar) {
            $kamar->delete();
        }

        $tipeKamar->clearMediaCollection('foto_interior');
        $tipeKamar->delete();

        unset($this->roomInputs[$tipeKamarId]);

        session()->flash('success', 'Tipe kamar berhasil dihapus.');
    }

    public function selesai(): Redirector|RedirectResponse|null
    {
        $this->resetErrorBag();

        if ($this->hasUnsavedRoomDrafts()) {
            $this->addError('general', 'Masih ada nomor kamar yang belum disimpan. Klik "Simpan Nomor Kamar" dulu atau kosongkan inputnya.');

            return null;
        }

        if ($this->hasUnsavedTypeDraft()) {
            $this->addError('general', 'Masih ada draft tipe kamar yang belum disimpan. Klik "Simpan Tipe Kamar" dulu atau kosongkan formnya.');

            return null;
        }

        session()->flash('success', 'Pengaturan kamar kos sudah tersimpan.');

        return redirect()->route('mitra.properti');
    }

    #[Layout('layouts.mitra.utama')]
    public function render(): View
    {
        return view('livewire.mitra.properti.kelola-kamar', [
            'kosan' => $this->currentKosan(),
        ]);
    }

    protected function currentKosan(): Kosan
    {
        return $this->pemilikProperti()
            ->kosan()
            ->with([
                'media',
                'tipeKamar.media',
                'tipeKamar.kamar' => fn ($query) => $query->orderBy('nomor_kamar'),
            ])
            ->findOrFail($this->kosanId);
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

    protected function ownedTipeKamar(int $tipeKamarId): TipeKamar
    {
        return TipeKamar::query()
            ->where('kosan_id', $this->kosanId)
            ->whereHas('kosan', function (Builder $query): void {
                $query->where('pemilik_properti_id', $this->pemilikProperti()->id);
            })
            ->findOrFail($tipeKamarId);
    }

    protected function ownedKamar(int $kamarId): Kamar
    {
        return Kamar::query()
            ->whereKey($kamarId)
            ->whereHas('tipeKamar', function (Builder $query): void {
                $query->where('kosan_id', $this->kosanId)
                    ->whereHas('kosan', function (Builder $kosanQuery): void {
                        $kosanQuery->where('pemilik_properti_id', $this->pemilikProperti()->id);
                    });
            })
            ->firstOrFail();
    }

    /**
     * @return Collection<int, string>
     */
    protected function sanitizeRoomInputs(int $tipeKamarId): Collection
    {
        return collect($this->roomInputs[$tipeKamarId] ?? [])
            ->map(static fn ($nomor): string => trim((string) $nomor))
            ->filter(static fn (string $nomor): bool => $nomor !== '')
            ->values();
    }

    protected function ensureRoomInputs(Kosan $kosan): void
    {
        foreach ($kosan->tipeKamar as $tipeKamar) {
            $this->roomInputs[$tipeKamar->id] ??= [''];
        }
    }

    protected function hasUnsavedRoomDrafts(): bool
    {
        foreach (array_keys($this->roomInputs) as $tipeKamarId) {
            if ($this->sanitizeRoomInputs((int) $tipeKamarId)->isNotEmpty()) {
                return true;
            }
        }

        return false;
    }

    protected function hasUnsavedTypeDraft(): bool
    {
        return trim($this->nama_tipe) !== ''
            || trim($this->harga_per_bulan) !== ''
            || trim($this->fasilitas_tipe) !== ''
            || $this->foto_interior !== null;
    }
}
