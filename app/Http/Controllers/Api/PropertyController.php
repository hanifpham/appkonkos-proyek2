<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kontrakan;
use App\Models\Kosan;

class PropertyController extends Controller
{
    public function index()
    {
        $kosan = Kosan::with(['tipeKamar.kamar', 'ulasan'])
            ->where('status', 'aktif')
            ->whereHas('tipeKamar.kamar', fn ($query) => $query->where('status_kamar', 'tersedia'))
            ->get()
            ->map(function ($item) {
                $hargaMin = $item->tipeKamar->min('harga_per_bulan') ?? 0;
                $hargaMax = $item->tipeKamar->max('harga_per_bulan') ?? 0;

                $foto = $item->getFirstMediaUrl('foto_properti', 'webp')
                    ?: 'https://via.placeholder.com/400x300';

                return [
                    'id'              => $item->id,
                    'nama'            => $item->nama_properti,
                    'alamat'          => $item->alamat_lengkap,
                    'harga'           => (int) $hargaMin,
                    'harga_max'       => (int) $hargaMax,
                    'period'          => 'bulan',
                    'tipe'            => 'Kosan',
                    'foto'            => str_replace('http://localhost', 'http://192.168.1.10:8000', $foto),
                    'rating'          => round($item->ulasan->avg('rating') ?? 0, 1),
                    'lat'             => (float) $item->latitude,
                    'lng'             => (float) $item->longitude,
                    'gender'          => $item->jenis_kos ?? '',
                    'available_count' => $item->tipeKamar
                        ->sum(fn ($tipe) => $tipe->kamar
                            ->where('status_kamar', 'tersedia')
                            ->count()),
                ];
            });

        $kontrakan = Kontrakan::with(['ulasan'])
            ->where('status', 'aktif')
            ->where('sisa_kamar', '>', 0)
            ->get()
            ->map(function ($item) {
                $foto = $item->getFirstMediaUrl('foto_properti', 'webp')
                    ?: 'https://via.placeholder.com/400x300';

                return [
                    'id'              => $item->id,
                    'nama'            => $item->nama_properti,
                    'alamat'          => $item->alamat_lengkap,
                    'harga'           => (int) $item->harga_sewa_tahun,
                    'harga_max'       => (int) $item->harga_sewa_tahun,
                    'period'          => 'tahun',
                    'tipe'            => 'Kontrakan',
                    'foto'            => str_replace('http://localhost', 'http://192.168.1.10:8000', $foto),
                    'rating'          => round($item->ulasan->avg('rating') ?? 0, 1),
                    'lat'             => (float) $item->latitude,
                    'lng'             => (float) $item->longitude,
                    'available_count' => (int) $item->sisa_kamar,
                ];
            });

        $all = $kosan->concat($kontrakan)->shuffle()->values();

        return response()->json(['success' => true, 'data' => $all]);
    }

    public function detailKosan($id)
    {
        $kosan = Kosan::with([
            'tipeKamar.kamar',
            'ulasan',
            'pemilikProperti.user',
        ])
            ->where('status', 'aktif')
            ->findOrFail($id);

        $fotos = $kosan->getMedia('foto_properti')
            ->map(fn ($media) => str_replace(
                'http://localhost',
                'http://192.168.1.10:8000',
                $media->getUrl('webp')
            ))->values();

        // Parse fasilitas_umum — support JSON array atau plain text dipisah koma
        $fasilitasUmum = $kosan->fasilitas_umum;
        if (is_string($fasilitasUmum)) {
            $decoded = json_decode($fasilitasUmum, true);
            $fasilitasUmum = is_array($decoded)
                ? $decoded
                : array_values(array_filter(array_map('trim', explode(',', $fasilitasUmum))));
        }
        $fasilitasUmum = $fasilitasUmum ?? [];

        return response()->json([
            'success' => true,
            'data'    => [
                'id'             => $kosan->id,
                'nama'           => $kosan->nama_properti,
                'alamat'         => $kosan->alamat_lengkap,
                'peraturan'      => $kosan->peraturan_kos ?? '',
                'fotos'          => $fotos,
                'rating'         => round($kosan->ulasan->avg('rating') ?? 0, 1),
                'lat'            => (float) $kosan->latitude,
                'lng'            => (float) $kosan->longitude,
                'tipe'           => 'Kosan',
                'gender'         => $kosan->jenis_kos ?? '',
                'no_wa'          => $kosan->pemilikProperti?->user?->no_wa ?? null,
                'fasilitas_umum' => $fasilitasUmum,

                'room_types' => $kosan->tipeKamar->map(function ($tipe) {
                    // Parse fasilitas_tipe — support JSON array atau plain text dipisah koma
                    $fasilitasTipe = $tipe->fasilitas_tipe;
                    if (is_string($fasilitasTipe)) {
                        $decoded = json_decode($fasilitasTipe, true);
                        $fasilitasTipe = is_array($decoded)
                            ? $decoded
                            : array_values(array_filter(array_map('trim', explode(',', $fasilitasTipe))));
                    }
                    $fasilitasTipe = $fasilitasTipe ?? [];

                    return [
                        'id'              => $tipe->id,
                        'name'            => $tipe->nama_tipe,
                        'price'           => (int) $tipe->harga_per_bulan,
                        'fasilitas'       => $fasilitasTipe,
                        'available_count' => $tipe->kamar
                            ->where('status_kamar', 'tersedia')
                            ->count(),
                        'rooms'           => $tipe->kamar->map(fn ($kamar) => [
                            'id'     => $kamar->id,
                            'number' => $kamar->nomor_kamar,
                            'status' => $kamar->status_kamar,
                        ])->values(),
                    ];
                })->values(),
            ],
        ]);
    }

    public function detailKontrakan($id)
    {
        $kontrakan = Kontrakan::with(['ulasan', 'pemilikProperti.user'])
            ->where('status', 'aktif')
            ->where('sisa_kamar', '>', 0)
            ->findOrFail($id);

        $fotos = $kontrakan->getMedia('foto_properti')
            ->map(fn ($media) => str_replace(
                'http://localhost',
                'http://192.168.1.10:8000',
                $media->getUrl('webp')
            ))->values();

        // Parse fasilitas — support JSON array atau plain text dipisah koma
        $fasilitas = $kontrakan->fasilitas;
        if (is_string($fasilitas)) {
            $decoded = json_decode($fasilitas, true);
            $fasilitas = is_array($decoded)
                ? $decoded
                : array_values(array_filter(array_map('trim', explode(',', $fasilitas))));
        }
        $fasilitas = $fasilitas ?? [];

        return response()->json([
            'success' => true,
            'data'    => [
                'id'         => $kontrakan->id,
                'nama'       => $kontrakan->nama_properti,
                'alamat'     => $kontrakan->alamat_lengkap,
                'peraturan'  => $kontrakan->peraturan_kontrakan ?? '',
                'harga'      => (int) $kontrakan->harga_sewa_tahun,
                'period'     => 'tahun',
                'fotos'      => $fotos,
                'rating'     => round($kontrakan->ulasan->avg('rating') ?? 0, 1),
                'lat'        => (float) $kontrakan->latitude,
                'lng'        => (float) $kontrakan->longitude,
                'tipe'       => 'Kontrakan',
                'no_wa'      => $kontrakan->pemilikProperti?->user?->no_wa ?? null,
                'fasilitas'  => $fasilitas,
                'sisa_kamar' => (int) $kontrakan->sisa_kamar,
            ],
        ]);
    }
}