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
        // Kos: hanya yang disetujui/aktif, eager-load media + tipeKamar + ulasan
        $kosanList = Kosan::query()
            ->where('status', 'aktif')
            ->with(['tipeKamar.kamar', 'ulasan', 'media'])
            ->latest()
            ->take(8)
            ->get()
            ->map(function (Kosan $kosan) {
                $tipeKamar = $kosan->tipeKamar;
                $hargaMin = $tipeKamar->min('harga_per_bulan');

                // Hitung sisa kamar tersedia dari semua tipe
                $sisaKamar = $tipeKamar->sum(fn ($tipe) => $tipe->kamar->where('status_kamar', 'tersedia')->count());

                // Ambil fasilitas dari tipe pertama
                $fasilitasTipe = $tipeKamar->first()?->fasilitas_tipe ?? '';

                // Rating rata-rata
                $avgRating = $kosan->ulasan->avg('rating');

                return (object) [
                    'id'          => $kosan->id,
                    'nama'        => $kosan->nama_properti,
                    'alamat'      => $kosan->alamat_lengkap,
                    'jenis_kos'   => $kosan->jenis_kos,
                    'harga_min'   => $hargaMin ? (int) $hargaMin : null,
                    'sisa_kamar'  => $sisaKamar,
                    'fasilitas'   => $fasilitasTipe,
                    'rating'      => $avgRating ? round($avgRating, 1) : null,
                    'jumlah_ulasan' => $kosan->ulasan->count(),
                    'foto'        => $kosan->getMediaDisplayUrl('foto_properti'),
                ];
            });

        // Kontrakan: hanya yang disetujui/aktif
        $kontrakanList = Kontrakan::query()
            ->where('status', 'aktif')
            ->with(['ulasan', 'media'])
            ->latest()
            ->take(8)
            ->get()
            ->map(function (Kontrakan $kontrakan) {
                $avgRating = $kontrakan->ulasan->avg('rating');

                return (object) [
                    'id'            => $kontrakan->id,
                    'nama'          => $kontrakan->nama_properti,
                    'alamat'        => $kontrakan->alamat_lengkap,
                    'harga_tahun'   => (int) $kontrakan->harga_sewa_tahun,
                    'sisa_kamar'    => (int) $kontrakan->sisa_kamar,
                    'fasilitas'     => $kontrakan->fasilitas ?? '',
                    'rating'        => $avgRating ? round($avgRating, 1) : null,
                    'jumlah_ulasan' => $kontrakan->ulasan->count(),
                    'foto'          => $kontrakan->getMediaDisplayUrl('foto_properti'),
                ];
            });

        return view('public.home', [
            'kosanList'     => $kosanList,
            'kontrakanList' => $kontrakanList,
        ]);
    }
}
