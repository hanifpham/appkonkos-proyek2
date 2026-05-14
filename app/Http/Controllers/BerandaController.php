<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Kontrakan;
use App\Models\Kosan;
use Illuminate\View\View;

class BerandaController extends Controller
{
    public function __invoke(): View
    {
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

        return view('public.home', compact('kosanList', 'kontrakanList'));
    }
}
