<?php

namespace App\Livewire\Front;

use Livewire\Component;
use App\Models\Kosan;
use App\Models\Kontrakan;
use App\Models\Booking;
use App\Models\TipeKamar;
use App\Models\Kamar;
use Illuminate\Support\Facades\Auth;

class DetailProperti extends Component
{
    public $tipe;
    public $propertiId;
    public $properti;
    public $selectedTipeKamarId = null;
    public $selectedKamarId = null;
    public $tanggalCheckIn;
    public $durasiSewa = 1;

    public function mount($tipe, $id)
    {
        $this->tipe = $tipe;
        $this->propertiId = $id;

        if ($tipe === 'kosan') {
            $this->properti = Kosan::with(['pemilikProperti.user', 'ulasan.user', 'tipeKamar.kamar'])->findOrFail($id);
            
            if ($this->properti->tipeKamar->isNotEmpty()) {
                $this->selectedTipeKamarId = $this->properti->tipeKamar->first()->id;
            }
        } elseif ($tipe === 'kontrakan') {
            $this->properti = Kontrakan::with(['pemilikProperti.user', 'ulasan.user'])->findOrFail($id);
        } else {
            abort(404);
        }
    }

    public function updatedSelectedTipeKamarId()
    {
        $this->selectedKamarId = null;
    }

    public function selectKamar($id, $status)
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
        ], [
            'tanggalCheckIn.required' => 'Tanggal Masuk harus diisi.',
            'tanggalCheckIn.after_or_equal' => 'Tanggal Masuk tidak boleh di masa lalu.',
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
        $booking->status_booking = 'menunggu_pembayaran';
        
        if ($this->tipe === 'kosan') {
            if (!$this->selectedKamarId) {
                session()->flash('error', 'Silakan pilih nomor kamar terlebih dahulu.');
                return;
            }

            $booking->tgl_selesai_sewa = \Carbon\Carbon::parse($this->tanggalCheckIn)->addMonths($this->durasiSewa);
            
            $kamar = Kamar::with('tipeKamar')->find($this->selectedKamarId);

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

    public function render()
    {
        return view('livewire.front.detail-properti')->layout('layouts.public');
    }
}
