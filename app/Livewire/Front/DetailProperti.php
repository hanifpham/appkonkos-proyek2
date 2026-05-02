<?php

namespace App\Livewire\Front;

use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Kontrakan;
use App\Models\Kosan;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class DetailProperti extends Component
{
    public $tipe;
    public $propertiId;
    public $properti;
    public $selectedTipeKamarId = null;
    public $selectedKamarId = null;
    public $tanggalCheckIn;
    public $durasiSewa = 1;

    public function mount(string $tipe, string $id): void
    {
        $this->tipe = $tipe;
        $this->propertiId = $id;
        $this->tanggalCheckIn = now()->toDateString();

        if ($tipe === 'kosan') {
            $this->properti = Kosan::query()
                ->where('status', 'aktif')
                ->with([
                    'media',
                    'pemilikProperti.user',
                    'ulasan.pencariKos.user',
                    'tipeKamar.media',
                    'tipeKamar.kamar',
                ])
                ->findOrFail($id);
            
            if ($this->properti->tipeKamar->isNotEmpty()) {
                $this->selectedTipeKamarId = $this->properti->tipeKamar->first()->id;
            }
        } elseif ($tipe === 'kontrakan') {
            $this->properti = Kontrakan::query()
                ->where('status', 'aktif')
                ->with([
                    'media',
                    'pemilikProperti.user',
                    'ulasan.pencariKos.user',
                ])
                ->findOrFail($id);
        } else {
            abort(404);
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
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'tanggalCheckIn' => 'required|date|after_or_equal:today',
            'durasiSewa' => 'required|integer|min:1|max:36',
        ], [
            'tanggalCheckIn.required' => 'Tanggal Masuk harus diisi.',
            'tanggalCheckIn.after_or_equal' => 'Tanggal Masuk tidak boleh di masa lalu.',
            'durasiSewa.required' => 'Durasi sewa harus dipilih.',
        ]);

        $user = Auth::user();
        if ($user->role !== 'pencari') {
            session()->flash('error', 'Hanya akun pencari yang dapat melakukan booking.');
            return;
        }

        $pencari = $user->pencariKos;
        if (!$pencari) {
            session()->flash('error', 'Profil Pencari Kos Anda belum lengkap.');
            return;
        }

        $booking = new Booking();
        $booking->pencari_kos_id = $pencari->id;
        $booking->tgl_mulai_sewa = $this->tanggalCheckIn;
        $booking->status_booking = 'pending';
        
        if ($this->tipe === 'kosan') {
            if (!$this->selectedKamarId) {
                session()->flash('error', 'Silakan pilih nomor kamar terlebih dahulu.');
                return;
            }

            $booking->tgl_selesai_sewa = \Carbon\Carbon::parse($this->tanggalCheckIn)->addMonths($this->durasiSewa);
            
            $kamar = Kamar::query()
                ->with('tipeKamar')
                ->whereHas('tipeKamar', fn ($query) => $query->where('kosan_id', $this->properti->id))
                ->find($this->selectedKamarId);

            if (!$kamar || $kamar->status_kamar !== 'tersedia') {
                session()->flash('error', 'Kamar tidak tersedia.');
                return;
            }

            $booking->kamar_id = $kamar->id;
            $booking->total_biaya = $kamar->tipeKamar->harga_per_bulan * $this->durasiSewa;
        } else {
            $booking->tgl_selesai_sewa = \Carbon\Carbon::parse($this->tanggalCheckIn)->addYears($this->durasiSewa);
            
            if ($this->properti->sisa_kamar < 1) {
                session()->flash('error', 'Mohon maaf, kontrakan ini sudah penuh.');
                return;
            }
            $booking->kontrakan_id = $this->properti->id;
            $booking->total_biaya = $this->properti->harga_sewa_tahun * $this->durasiSewa;
        }

        $booking->save();

        return redirect()->route('pencari.pembayaran.show', $booking->id);
    }

    public function profilePhotoUrlFor(?User $user, string $fallbackName = 'User'): string
    {
        $name = $user?->name ?? $fallbackName;
        $timestamp = $user?->updated_at?->timestamp ?? now()->timestamp;

        if (is_string($user?->profile_photo_path) && $user->profile_photo_path !== '') {
            $normalizedPath = $this->normalizeStoragePath($user->profile_photo_path);
            $this->ensurePublicStorageFile($normalizedPath);

            $baseUrl = rtrim(request()->getBaseUrl(), '/');
            $storageUrl = ($baseUrl === '' ? '' : $baseUrl).'/storage/'.$normalizedPath;

            return $storageUrl.'?v='.$timestamp;
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=113C7A&background=EBF4FF';
    }

    public function render(): View
    {
        return view('livewire.front.detail-properti')->layout('layouts.public');
    }

    protected function normalizeStoragePath(string $path): string
    {
        $normalizedPath = ltrim($path, '/');

        return preg_replace('#^(storage/|public/storage/)#', '', $normalizedPath) ?? $normalizedPath;
    }

    protected function ensurePublicStorageFile(string $path): void
    {
        if ($path === '') {
            return;
        }

        $sourcePath = Storage::disk('public')->path($path);
        $publicPath = public_path('storage/'.$path);

        if (! File::exists($sourcePath)) {
            return;
        }

        File::ensureDirectoryExists(dirname($publicPath));

        if (! File::exists($publicPath) || File::size($publicPath) !== File::size($sourcePath)) {
            File::copy($sourcePath, $publicPath);
        }
    }
}
