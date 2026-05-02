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
            ->where('status', 'aktif') // ← tambah ini
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
                    'foto'      => str_replace('http://localhost', 'http://192.168.1.9:8000', $foto),
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
                    'foto'      => str_replace('http://localhost', 'http://192.168.1.9:8000', $foto),
                    'rating'    => round($item->ulasan->avg('rating') ?? 0, 1),
                    'lat'       => (float) $item->latitude,
                    'lng'       => (float) $item->longitude,
                ];
            });

        $all = $kosan->concat($kontrakan)->shuffle();

        return response()->json(['data' => $all]);
    }
}