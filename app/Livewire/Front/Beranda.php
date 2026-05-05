<?php

namespace App\Livewire\Front;

use App\Models\Favorit;
use App\Models\Kontrakan;
use App\Models\Kosan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Beranda extends Component
{
    public $favoritIds = [];

    public function mount()
    {
        $this->loadFavoritIds();
    }

    public function loadFavoritIds()
    {
        if (Auth::check()) {
            // Kita ambil favoritable_id. Untuk membedakan Kosan dan Kontrakan jika ID-nya sama, 
            // idealnya dicek favoritable_type juga. Namun sesuai instruksi user kita simpan ke satu array.
            $this->favoritIds = Favorit::where('user_id', Auth::id())
                ->pluck('favoritable_id')
                ->toArray();
        } else {
            $this->favoritIds = [];
        }
    }

    public function toggleFavorit($propertiId, $tipe)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $tipeClass = $tipe === 'kosan' ? Kosan::class : Kontrakan::class;

        $favorit = Favorit::where('user_id', Auth::id())
            ->where('favoritable_type', $tipeClass)
            ->where('favoritable_id', $propertiId)
            ->first();

        if ($favorit) {
            $favorit->delete();
        } else {
            Favorit::create([
                'user_id' => Auth::id(),
                'favoritable_type' => $tipeClass,
                'favoritable_id' => $propertiId,
            ]);
        }

        $this->loadFavoritIds();
    }

    public function render()
    {
        // Ambil data yang sama seperti di BerandaController agar tampilan tetap sama
        $kosanList = Kosan::query()
            ->where('status', 'aktif')
            ->with(['tipeKamar.kamar', 'ulasan', 'media'])
            ->latest()
            ->take(8)
            ->get()
            ->map(function (Kosan $kosan) {
                $tipeKamar = $kosan->tipeKamar;
                $hargaMin = $tipeKamar->min('harga_per_bulan');
                $sisaKamar = $tipeKamar->sum(fn ($tipe) => $tipe->kamar->where('status_kamar', 'tersedia')->count());
                
                return (object) [
                    'id'          => $kosan->id,
                    'nama'        => $kosan->nama_properti,
                    'alamat'      => $kosan->alamat_lengkap,
                    'jenis_kos'   => $kosan->jenis_kos,
                    'harga_min'   => $hargaMin ? (int) $hargaMin : null,
                    'sisa_kamar'  => $sisaKamar,
                    'fasilitas'   => $tipeKamar->first()?->fasilitas_tipe ?? '',
                    'rating'      => $kosan->ulasan->avg('rating') ? round($kosan->ulasan->avg('rating'), 1) : null,
                    'foto'        => $kosan->getMediaDisplayUrl('foto_properti'),
                ];
            });

        $kontrakanList = Kontrakan::query()
            ->where('status', 'aktif')
            ->with(['ulasan', 'media'])
            ->latest()
            ->take(8)
            ->get()
            ->map(function (Kontrakan $kontrakan) {
                return (object) [
                    'id'            => $kontrakan->id,
                    'nama'          => $kontrakan->nama_properti,
                    'alamat'        => $kontrakan->alamat_lengkap,
                    'harga_tahun'   => (int) $kontrakan->harga_sewa_tahun,
                    'sisa_kamar'    => (int) $kontrakan->sisa_kamar,
                    'fasilitas'     => $kontrakan->fasilitas ?? '',
                    'rating'        => $kontrakan->ulasan->avg('rating') ? round($kontrakan->ulasan->avg('rating'), 1) : null,
                    'foto'          => $kontrakan->getMediaDisplayUrl('foto_properti'),
                ];
            });

        return view('livewire.front.beranda', [
            'kosanList'     => $kosanList,
            'kontrakanList' => $kontrakanList,
        ]);
    }
}
