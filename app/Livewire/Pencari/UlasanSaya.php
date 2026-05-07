<?php

namespace App\Livewire\Pencari;

use App\Models\Booking;
use App\Models\Ulasan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

class UlasanSaya extends Component
{
    public int $rating = 5;
    public string $komentar = '';
    public bool $is_anonymous = false;
    
    public ?string $selectedBookingId = null;
    public ?int $selectedPencariKosId = null;
    public ?int $selectedKosanId = null;
    public ?int $selectedKontrakanId = null;

    #[Layout('layouts.public')]
    public function render()
    {
        $user = Auth::user();
        
        // Asumsi bahwa setiap user memiliki relasi pencariKos
        $pencariKos = $user->pencariKos;

        if (!$pencariKos) {
            return view('livewire.pencari.ulasan-saya', [
                'pesananBelumDiulas' => collect(),
                'riwayatUlasan' => collect(),
            ]);
        }

        // Pesanan selesai/berhasil yang belum ada di tabel ulasan
        $pesananBelumDiulas = Booking::with(['kamar.tipeKamar.kosan', 'kontrakan'])
            ->where('pencari_kos_id', $pencariKos->id)
            ->whereIn('status_booking', ['lunas'])
            ->whereDoesntHave('ulasan')
            ->get();

        // Riwayat ulasan oleh user ini (pencari kos ini)
        $riwayatUlasan = Ulasan::with(['kosan', 'kontrakan'])
            ->where('pencari_kos_id', $pencariKos->id)
            ->latest()
            ->get();

        return view('livewire.pencari.ulasan-saya', [
            'pesananBelumDiulas' => $pesananBelumDiulas,
            'riwayatUlasan' => $riwayatUlasan,
        ]);
    }

    public function pilihPesanan(string $bookingId, int $pencariKosId, mixed $kosanId = null, mixed $kontrakanId = null)
    {
        $this->selectedBookingId = $bookingId;
        $this->selectedPencariKosId = $pencariKosId;
        
        // $kosanId dan $kontrakanId bisa null/kosong tergantung tipe propertinya
        $this->selectedKosanId = empty($kosanId) ? null : (int) $kosanId;
        $this->selectedKontrakanId = empty($kontrakanId) ? null : (int) $kontrakanId;
        
        $this->rating = 5;
        $this->komentar = '';
        $this->is_anonymous = false;
    }

    public function simpanUlasan()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|min:5|max:500',
            'selectedBookingId' => 'required',
            'selectedPencariKosId' => 'required',
        ], [
            'komentar.required' => 'Komentar ulasan wajib diisi.',
            'komentar.min' => 'Komentar minimal 5 karakter.',
        ]);

        Ulasan::create([
            'booking_id' => $this->selectedBookingId,
            'pencari_kos_id' => $this->selectedPencariKosId,
            'kosan_id' => $this->selectedKosanId,
            'kontrakan_id' => $this->selectedKontrakanId,
            'rating' => $this->rating,
            'komentar' => $this->komentar,
            'is_anonymous' => $this->is_anonymous,
        ]);

        $this->reset([
            'rating', 'komentar', 'is_anonymous', 
            'selectedBookingId', 'selectedPencariKosId', 
            'selectedKosanId', 'selectedKontrakanId'
        ]);
        
        session()->flash('success', 'Terima kasih! Ulasan Anda berhasil dikirim.');
    }
}
