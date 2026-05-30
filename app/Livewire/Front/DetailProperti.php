<?php

namespace App\Livewire\Front;

use App\Models\Favorit;
use App\Models\Kamar;
use App\Models\Kontrakan;
use App\Models\Kosan;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class DetailProperti extends Component
{
    public string $tipe;

    public string $propertiId;

    public mixed $properti;

    public ?int $selectedTipeKamarId = null;

    public ?int $selectedKamarId = null;

    public string $tanggalCheckIn;

    public int $durasiSewa = 1;

    public bool $isFavorit = false;

    public function mount(string $tipe, string $id): void
    {
        $this->tipe = $tipe;
        $this->propertiId = $id;
        $this->tanggalCheckIn = now()->toDateString();

        if ($tipe === 'kosan') {
            $this->properti = Kosan::query()
                ->where('status', 'aktif')
                ->whereHas('tipeKamar.kamar', fn ($query) => $query->where('status_kamar', 'tersedia'))
                ->with([
                    'media',
                    'pemilikProperti.user',
                    'ulasan.pencariKos.user',
                    'tipeKamar.media',
                    'tipeKamar.kamar',
                ])
                ->findOrFail($id);

            if ($this->properti->tipeKamar->isNotEmpty()) {
                $availableType = $this->properti->tipeKamar
                    ->first(fn ($type) => $type->kamar->contains('status_kamar', 'tersedia'));

                $this->selectedTipeKamarId = $availableType?->id;
            }
        } elseif ($tipe === 'kontrakan') {
            $this->properti = Kontrakan::query()
                ->where('status', 'aktif')
                ->where('sisa_kamar', '>', 0)
                ->with([
                    'media',
                    'pemilikProperti.user',
                    'ulasan.pencariKos.user',
                ])
                ->findOrFail($id);
        } else {
            abort(404);
        }

        if (Auth::check()) {
            $this->isFavorit = Favorit::query()
                ->where('user_id', Auth::id())
                ->where('favoritable_type', get_class($this->properti))
                ->where('favoritable_id', $this->properti->id)
                ->exists();
        }
    }

    public function toggleFavorit()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $existing = Favorit::query()
            ->where('user_id', Auth::id())
            ->where('favoritable_type', get_class($this->properti))
            ->where('favoritable_id', $this->properti->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $this->isFavorit = false;
        } else {
            Favorit::create([
                'user_id' => Auth::id(),
                'favoritable_type' => get_class($this->properti),
                'favoritable_id' => $this->properti->id,
            ]);
            $this->isFavorit = true;
        }
    }

    public function updatedSelectedTipeKamarId(): void
    {
        $this->selectedKamarId = null;
    }

    public function selectKamar(int $id, string $status): void
    {
        if ($status === 'tersedia') {
            $this->selectedKamarId = $id;
        }
    }

    public function buatBooking()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if ($user->role !== 'pencari') {
            session()->flash('error', 'Hanya akun pencari yang dapat melakukan booking.');

            return;
        }

        $pencari = $user->pencariKos;
        if (! $pencari) {
            session()->flash('error', 'Profil Pencari Kos Anda belum lengkap.');

            return;
        }

        if ($this->tipe === 'kosan') {
            if (! $this->selectedKamarId) {
                session()->flash('error', 'Silakan pilih nomor kamar terlebih dahulu.');

                return;
            }

            // Validasi ketat keamanan Backend (Mencegah user bypass inspect element)
            $kamarCheck = Kamar::find($this->selectedKamarId);
            if (! $kamarCheck || $kamarCheck->status_kamar !== 'tersedia') {
                session()->flash('error', 'Mohon maaf, kamar ini baru saja habis dipesan.');

                return;
            }

            return redirect()->route('pencari.checkout', ['kamar_id' => $this->selectedKamarId]);
        } else {
            // Validasi ketat keamanan Backend untuk Kontrakan
            $kontrakanCheck = Kontrakan::find($this->propertiId);
            if (! $kontrakanCheck || $kontrakanCheck->sisa_kamar <= 0 || $kontrakanCheck->status !== 'aktif') {
                session()->flash('error', 'Mohon maaf, unit kontrakan ini baru saja habis dipesan.');

                return;
            }

            return redirect()->route('pencari.checkout', ['kontrakan_id' => $this->propertiId]);
        }
    }

    public function profilePhotoUrlFor(?User $user, string $fallbackName = 'User'): string
    {
        $name = $user?->name ?? $fallbackName;
        $timestamp = $user?->updated_at?->timestamp ?? now()->timestamp;

        if ($user !== null) {
            $mediaUrl = $user->getFirstMediaUrl('foto_profil');

            if ($mediaUrl !== '') {
                return $mediaUrl . '?v=' . $timestamp;
            }
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=113C7A&background=EBF4FF';
    }

    #[Layout('layouts.public')]
    public function render(): View
    {
        return view('livewire.front.detail-properti');
    }
}
