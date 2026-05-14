<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Kamar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'kamar_id' => 'required|exists:kamar,id',
            'tgl_mulai_sewa' => 'required|date',
            'durasi_bulan' => 'required|integer|min:1',
        ]);

        $user = $request->user()->load('pencariKos');
        $pencari = $user->pencariKos;

        $isComplete =
            !empty($user->profile_photo_path) &&
            !empty($user->name) &&
            !empty($user->email) &&
            !empty($user->no_telepon) &&
            $pencari &&
            !empty($pencari->no_wa) &&
            !empty($pencari->jenis_kelamin) &&
            !empty($pencari->pekerjaan) &&
            !empty($pencari->domisili);

        if (!$isComplete) {
            return response()->json([
                'success' => false,
                'message' => 'Profil belum lengkap. Lengkapi profil terlebih dahulu.',
            ], 422);
        }

        return DB::transaction(function () use ($request, $pencari) {
            $kamar = Kamar::with('tipeKamar')
                ->lockForUpdate()
                ->findOrFail($request->kamar_id);

            if ($kamar->status_kamar !== 'tersedia') {
                return response()->json([
                    'success' => false,
                    'message' => 'Kamar sudah tidak tersedia.',
                ], 422);
            }

            $tglMulai = Carbon::parse($request->tgl_mulai_sewa);
            $tglSelesai = $tglMulai->copy()->addMonths((int) $request->durasi_bulan);

            $totalBiaya = $kamar->tipeKamar->harga_per_bulan * (int) $request->durasi_bulan;

            $booking = Booking::create([
                'id' => (string) Str::uuid(),
                'pencari_kos_id' => $pencari->id,
                'kamar_id' => $kamar->id,
                'kontrakan_id' => null,
                'tgl_mulai_sewa' => $tglMulai->toDateString(),
                'tgl_selesai_sewa' => $tglSelesai->toDateString(),
                'total_biaya' => $totalBiaya,
                'status_booking' => 'pending',
            ]);

            $kamar->update([
                'status_kamar' => 'dihuni',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibuat.',
                'data' => $booking,
            ], 201);
        });
    }
}