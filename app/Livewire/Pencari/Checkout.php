<?php

declare(strict_types=1);

namespace App\Livewire\Pencari;

use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Kontrakan;
use App\Services\MidtransService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;

class Checkout extends Component
{
    public ?int $kamar_id = null;
    public ?int $kontrakan_id = null;

    public string $tipeProperti = '';
    public string $namaProperti = '';
    public string $namaTipe = '';
    public string $nomorKamar = '';
    public int $hargaPerBulan = 0;
    public string $fotoUrl = '';

    public string $tanggal_masuk = '';
    public int $durasi_sewa = 1;
    public string $catatan = '';

    public int $biayaLayanan = 10000;

    public bool $setuju_aturan = false;
    public array $aturanProperti = [];

    public function mount()
    {
        $this->kamar_id = (int) request()->query('kamar_id') ?: null;
        $this->kontrakan_id = (int) request()->query('kontrakan_id') ?: null;

        if (!$this->kamar_id && !$this->kontrakan_id) {
            abort(404, 'Properti tidak ditemukan');
        }

        if ($this->kamar_id) {
            $kamar = Kamar::with('tipeKamar.kosan')->findOrFail($this->kamar_id);
            $this->tipeProperti = 'kosan';
            $this->namaProperti = $kamar->tipeKamar->kosan->nama_properti;
            $this->namaTipe = $kamar->tipeKamar->nama_tipe;
            $this->nomorKamar = $kamar->nomor_kamar;
            $this->hargaPerBulan = $kamar->tipeKamar->harga_per_bulan;

            $kosan = $kamar->tipeKamar->kosan;
            $this->fotoUrl = $kosan->getFirstMediaUrl('foto_properti', 'webp') ?: $kosan->getFirstMediaUrl('foto_properti');

            $aturanString = trim((string) $kosan->peraturan_kos);
            if (empty($aturanString)) {
                $aturanString = "Dilarang membawa hewan peliharaan\nDilarang membuat keributan di malam hari\nTamu dilarang menginap tanpa izin";
            }
            $this->aturanProperti = array_map('trim', preg_split('/[\r\n,]+/', $aturanString, -1, PREG_SPLIT_NO_EMPTY));
        } elseif ($this->kontrakan_id) {
            $kontrakan = Kontrakan::findOrFail($this->kontrakan_id);
            $this->tipeProperti = 'kontrakan';
            $this->namaProperti = $kontrakan->nama_properti;
            $this->hargaPerBulan = (int) ($kontrakan->harga_sewa_tahun / 12);
            $this->durasi_sewa = 12; // Default 1 tahun untuk kontrakan

            $this->fotoUrl = $kontrakan->getFirstMediaUrl('foto_properti', 'webp') ?: $kontrakan->getFirstMediaUrl('foto_properti');

            $aturanString = trim((string) $kontrakan->peraturan_kontrakan);
            if (empty($aturanString)) {
                $aturanString = "Menjaga kebersihan lingkungan\nMembayar iuran sampah/keamanan bulanan\nDilarang mengubah struktur bangunan tanpa izin";
            }
            $this->aturanProperti = array_map('trim', preg_split('/[\r\n,]+/', $aturanString, -1, PREG_SPLIT_NO_EMPTY));
        }

        $this->tanggal_masuk = now()->format('Y-m-d');
    }

    #[Computed]
    public function totalPembayaran()
    {
        return ($this->hargaPerBulan * $this->durasi_sewa) + $this->biayaLayanan;
    }

    #[Computed]
    public function tanggalKeluar()
    {
        if (!$this->tanggal_masuk) {
            return null;
        }
        return Carbon::parse($this->tanggal_masuk)->addMonths($this->durasi_sewa)->format('d F Y');
    }

    #[Computed]
    public function hargaSewa()
    {
        return $this->hargaPerBulan * $this->durasi_sewa;
    }

    public function prosesPembayaran(MidtransService $midtrans)
    {
        $this->validate([
            'tanggal_masuk' => 'required|date|after_or_equal:today',
            'durasi_sewa' => 'required|integer|min:1',
            'setuju_aturan' => 'accepted',
        ], [
            'setuju_aturan.accepted' => 'Anda harus menyetujui aturan kos untuk melanjutkan.',
        ]);

        $user = Auth::user();
        $pencariKos = $user->pencariKos;

        if (!$pencariKos) {
            session()->flash('error', 'Akun Anda belum melengkapi profil pencari kos.');
            return;
        }

        $tglMulai = Carbon::parse($this->tanggal_masuk);
        $tglSelesai = $tglMulai->copy()->addMonths($this->durasi_sewa);

        $booking = Booking::create([
            'id' => (string) Str::uuid(),
            'pencari_kos_id' => $pencariKos->id,
            'kamar_id' => $this->kamar_id,
            'kontrakan_id' => $this->kontrakan_id,
            'tgl_mulai_sewa' => $tglMulai,
            'tgl_selesai_sewa' => $tglSelesai,
            'total_biaya' => $this->totalPembayaran,
            'status_booking' => 'pending', // 'menunggu_pembayaran' will be derived from pembayaran status
        ]);

        // Simpan catatan jika diperlukan di masa depan (saat ini tabel booking tidak punya kolom catatan)
        // Kita bisa menambahkannya ke kolom notes jika ada, atau abaikan.

        try {
            $payment = $midtrans->createOrRefreshTransaction($booking);

            if ($payment && $payment->snap_token) {
                $this->dispatch('pay-midtrans', token: $payment->snap_token);
            } else {
                session()->flash('error', 'Token pembayaran gagal dibuat.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan sistem pembayaran: ' . $e->getMessage());
        }
    }

    #[Layout('layouts.public')]
    public function render()
    {
        return view('livewire.pencari.checkout');
    }
}
