<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Kosan;
use App\Models\Kontrakan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Collection;

class CariController extends Controller
{
    public function index(Request $request): View
    {
        $tipe = $request->query('tipe', 'Semua Tipe');
        $lokasi = $request->query('lokasi');
        $harga = $request->query('harga', 'Rentang Harga');

        $results = collect();

        // Cari Kos
        if ($tipe === 'Semua Tipe' || strtolower($tipe) === 'kos') {
            $kosanQuery = Kosan::query()
                ->where('status', 'aktif')
                ->with(['tipeKamar.kamar', 'ulasan', 'media']);

            if (!empty($lokasi)) {
                $kosanQuery->where(function ($q) use ($lokasi) {
                    $q->where('nama_properti', 'like', "%{$lokasi}%")
                      ->orWhere('alamat_lengkap', 'like', "%{$lokasi}%");
                });
            }

            $kosanList = $kosanQuery->get()->filter(function ($kosan) use ($harga) {
                $tipeKamar = $kosan->tipeKamar;
                $hargaMin = $tipeKamar->min('harga_per_bulan');

                if ($harga === '< Rp 1 Juta' && $hargaMin >= 1000000) return false;
                if ($harga === 'Rp 1 - 2 Juta' && ($hargaMin < 1000000 || $hargaMin > 2000000)) return false;
                if ($harga === 'Rp 2 - 3 Juta' && ($hargaMin < 2000000 || $hargaMin > 3000000)) return false;
                if ($harga === '> Rp 3 Juta' && $hargaMin <= 3000000) return false;

                $sisaKamar = $tipeKamar->sum(fn ($tipe) => $tipe->kamar->where('status_kamar', 'tersedia')->count());
                $avgRating = $kosan->ulasan->avg('rating');

                $kosan->tipe_properti = 'kos';
                $kosan->harga_tampil = $hargaMin;
                $kosan->sisa_kamar_tampil = $sisaKamar;
                $kosan->rating_tampil = $avgRating ? round($avgRating, 1) : null;
                $kosan->foto_tampil = $kosan->getMediaDisplayUrl('foto_properti');
                
                return true;
            });

            $results = $results->concat($kosanList);
        }

        // Cari Kontrakan
        if ($tipe === 'Semua Tipe' || strtolower($tipe) === 'kontrakan') {
            $kontrakanQuery = Kontrakan::query()
                ->where('status', 'aktif')
                ->with(['ulasan', 'media']);

            if (!empty($lokasi)) {
                $kontrakanQuery->where(function ($q) use ($lokasi) {
                    $q->where('nama_properti', 'like', "%{$lokasi}%")
                      ->orWhere('alamat_lengkap', 'like', "%{$lokasi}%");
                });
            }

            $kontrakanList = $kontrakanQuery->get()->filter(function ($kontrakan) use ($harga) {
                // Untuk kontrakan, rentang harga biasanya per tahun, jadi kita sesuaikan filternya
                // Asumsi budget per bulan x 12
                $hargaTahun = $kontrakan->harga_sewa_tahun;
                
                if ($harga === '< Rp 1 Juta' && $hargaTahun >= 12000000) return false;
                if ($harga === 'Rp 1 - 2 Juta' && ($hargaTahun < 12000000 || $hargaTahun > 24000000)) return false;
                if ($harga === 'Rp 2 - 3 Juta' && ($hargaTahun < 24000000 || $hargaTahun > 36000000)) return false;
                if ($harga === '> Rp 3 Juta' && $hargaTahun <= 36000000) return false;

                $avgRating = $kontrakan->ulasan->avg('rating');

                $kontrakan->tipe_properti = 'kontrakan';
                $kontrakan->harga_tampil = $hargaTahun;
                $kontrakan->sisa_kamar_tampil = $kontrakan->sisa_kamar;
                $kontrakan->rating_tampil = $avgRating ? round($avgRating, 1) : null;
                $kontrakan->foto_tampil = $kontrakan->getMediaDisplayUrl('foto_properti');

                return true;
            });

            $results = $results->concat($kontrakanList);
        }

        return view('public.cari', [
            'results' => $results,
            'request' => $request
        ]);
    }
}
