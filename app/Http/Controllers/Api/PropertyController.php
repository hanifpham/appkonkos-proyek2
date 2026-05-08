<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kontrakan;
use App\Models\Kosan;

class PropertyController extends Controller
{
    public function index()
    {
        $kosan = Kosan::with(['tipeKamar', 'ulasan'])
            ->where('status', 'aktif')
            ->get()
            ->map(function ($item) {
                $hargaMin = $item->tipeKamar->min('harga_per_bulan') ?? 0;
                $hargaMax = $item->tipeKamar->max('harga_per_bulan') ?? 0;

                $foto = $item->getFirstMediaUrl('foto_properti', 'webp')
                    ?: 'https://via.placeholder.com/400x300';

                return [
                    'id'        => $item->id,
                    'nama'      => $item->nama_properti,
                    'alamat'    => $item->alamat_lengkap,
                    'harga'     => (int) $hargaMin,
                    'harga_max' => (int) $hargaMax,
                    'period'    => 'bulan',
                    'tipe'      => 'Kosan',
                    'foto'      => str_replace('http://localhost', 'http://10.0.170.227:8000', $foto),
                    'rating'    => round($item->ulasan->avg('rating') ?? 0, 1),
                    'lat'       => (float) $item->latitude,
                    'lng'       => (float) $item->longitude,
                    'gender'    => $item->jenis_kos ?? '',
                ];
            });

        $kontrakan = Kontrakan::with(['ulasan'])
            ->where('status', 'aktif')
            ->get()
            ->map(function ($item) {
                $foto = $item->getFirstMediaUrl('foto_properti', 'webp')
                    ?: 'https://via.placeholder.com/400x300';

                return [
                    'id'        => $item->id,
                    'nama'      => $item->nama_properti,
                    'alamat'    => $item->alamat_lengkap,
                    'harga'     => (int) $item->harga_sewa_tahun,
                    'harga_max' => (int) $item->harga_sewa_tahun,
                    'period'    => 'tahun',
                    'tipe'      => 'Kontrakan',
                    'foto'      => str_replace('http://localhost', 'http://10.0.170.227:8000', $foto),
                    'rating'    => round($item->ulasan->avg('rating') ?? 0, 1),
                    'lat'       => (float) $item->latitude,
                    'lng'       => (float) $item->longitude,
                ];
            });

        $all = $kosan->concat($kontrakan)->shuffle()->values();

        return response()->json([
            'success' => true,
            'data' => $all,
        ]);
    }

    public function detailKosan($id)
    {
        $kosan = Kosan::with([
                'tipeKamar.kamar',
                'ulasan',
            ])
            ->where('status', 'aktif')
            ->findOrFail($id);

        $foto = $kosan->getFirstMediaUrl('foto_properti', 'webp')
            ?: 'https://via.placeholder.com/400x300';

        return response()->json([
            'success' => true,
            'data' => [
                'id'        => $kosan->id,
                'nama'      => $kosan->nama_properti,
                'alamat'    => $kosan->alamat_lengkap,
                'peraturan' => $kosan->peraturan_kos ?? '',
                'foto'      => str_replace('http://localhost', 'http://10.0.170.227:8000', $foto),
                'rating'    => round($kosan->ulasan->avg('rating') ?? 0, 1),
                'lat'       => (float) $kosan->latitude,
                'lng'       => (float) $kosan->longitude,
                'tipe'      => 'Kosan',
                'gender'    => $kosan->jenis_kos ?? '',

                'room_types' => $kosan->tipeKamar->map(function ($tipe) {
                    return [
                        'id'              => $tipe->id,
                        'name'            => $tipe->nama_tipe,
                        'price'           => (int) $tipe->harga_per_bulan,
                        'description'     => $tipe->fasilitas_tipe,
                        'available_count' => $tipe->kamar
                            ->where('status_kamar', 'tersedia')
                            ->count(),

                        'rooms' => $tipe->kamar->map(function ($kamar) {
                            return [
                                'id'     => $kamar->id,
                                'number' => $kamar->nomor_kamar,
                                'status' => $kamar->status_kamar,
                            ];
                        })->values(),
                    ];
                })->values(),
            ],
        ]);
    }

    public function detailKontrakan($id)
    {
        $kontrakan = Kontrakan::with(['ulasan'])
            ->where('status', 'aktif')
            ->findOrFail($id);

        $foto = $kontrakan->getFirstMediaUrl('foto_properti', 'webp')
            ?: 'https://via.placeholder.com/400x300';

        return response()->json([
            'success' => true,
            'data' => [
                'id'        => $kontrakan->id,
                'nama'      => $kontrakan->nama_properti,
                'alamat'    => $kontrakan->alamat_lengkap,
                'peraturan' => $kontrakan->peraturan_kontrakan ?? '',
                'harga'     => (int) $kontrakan->harga_sewa_tahun,
                'period'    => 'tahun',
                'foto'      => str_replace('http://localhost', 'http://10.0.170.227:8000', $foto),
                'rating'    => round($kontrakan->ulasan->avg('rating') ?? 0, 1),
                'lat'       => (float) $kontrakan->latitude,
                'lng'       => (float) $kontrakan->longitude,
                'tipe'      => 'Kontrakan',
            ],
        ]);
    }
}