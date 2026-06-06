<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use App\Models\Booking;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'tipe'        => 'required|in:kosan,kontrakan',
            'properti_id' => 'required|integer',
        ]);

        $query = Ulasan::with(['pencariKos.user']);

        if ($request->tipe === 'kosan') {
            $query->where('kosan_id', $request->properti_id);
        } else {
            $query->where('kontrakan_id', $request->properti_id);
        }

        $ulasan = $query->latest()->get()->map(function ($item) {
            $namaUser = $item->is_anonymous
                ? 'Pengguna Anonim'
                : ($item->pencariKos?->user?->name ?? 'Anonim');

            $fotoUser = $item->is_anonymous
                ? null
                : ($item->pencariKos?->user?->profile_photo_url ?? null);

            return [
                'id'             => $item->id,
                'rating'         => $item->rating,
                'komentar'       => $item->komentar,
                'created_at'     => $item->created_at->diffForHumans(),
                'nama_user'      => $namaUser,
                'foto_user'      => $fotoUser,
                'is_anonymous'   => $item->is_anonymous,
                'balasan_pemilik'=> $item->balasan_pemilik ?? null,
            ];
        });

        return response()->json([
            'success'   => true,
            'data'      => $ulasan,
            'rata_rata' => round($ulasan->avg('rating') ?? 0, 1),
            'total'     => $ulasan->count(),
        ]);
    }

    public function cekBolehReview(Request $request)
    {
        $request->validate([
            'tipe'        => 'required|in:kosan,kontrakan',
            'properti_id' => 'required|integer',
        ]);

        $user = $request->user();
        $pencariKos = $user->pencariKos;

        if (!$pencariKos) {
            return response()->json([
                'boleh'         => false,
                'sudah_review'  => false,
                'sudah_booking' => false,
            ]);
        }

        $bookingQuery = Booking::where('pencari_kos_id', $pencariKos->id)
            ->where('status_booking', 'lunas');

        if ($request->tipe === 'kosan') {
            // Booking kosan: kamar_id → kamar → tipeKamar → kosan_id
            $sudahBooking = (clone $bookingQuery)
                ->whereHas('kamar.tipeKamar', function ($q) use ($request) {
                    $q->where('kosan_id', $request->properti_id);
                })->exists();
        } else {
            $sudahBooking = (clone $bookingQuery)
                ->where('kontrakan_id', $request->properti_id)
                ->exists();
        }

        $sudahReview = false;
        if ($sudahBooking) {
            if ($request->tipe === 'kosan') {
                $bookingIds = Booking::where('pencari_kos_id', $pencariKos->id)
                    ->where('status_booking', 'lunas')
                    ->whereHas('kamar.tipeKamar', function ($q) use ($request) {
                        $q->where('kosan_id', $request->properti_id);
                    })
                    ->pluck('id');
            } else {
                $bookingIds = Booking::where('pencari_kos_id', $pencariKos->id)
                    ->where('status_booking', 'lunas')
                    ->where('kontrakan_id', $request->properti_id)
                    ->pluck('id');
            }

            $sudahReview = Ulasan::whereIn('booking_id', $bookingIds)
                ->where(function ($q) use ($request) {
                    if ($request->tipe === 'kosan') {
                        $q->where('kosan_id', $request->properti_id);
                    } else {
                        $q->where('kontrakan_id', $request->properti_id);
                    }
                })
                ->exists();
        }

        return response()->json([
            'boleh'         => $sudahBooking && !$sudahReview,
            'sudah_review'  => $sudahReview,
            'sudah_booking' => $sudahBooking,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe'         => 'required|in:kosan,kontrakan',
            'properti_id'  => 'required|integer',
            'rating'       => 'required|integer|min:1|max:5',
            'komentar'     => 'required|string|min:10|max:500',
            'is_anonymous' => 'boolean',
        ]);

        $user = $request->user();
        $pencariKos = $user->pencariKos;

        if (!$pencariKos) {
            return response()->json([
                'success' => false,
                'message' => 'Profil tidak ditemukan.',
            ], 403);
        }

        $bookingQuery = Booking::where('pencari_kos_id', $pencariKos->id)
            ->where('status_booking', 'lunas');

        if ($request->tipe === 'kosan') {
            // Booking kosan: kamar_id → kamar → tipeKamar → kosan_id
            $booking = (clone $bookingQuery)
                ->whereHas('kamar.tipeKamar', function ($q) use ($request) {
                    $q->where('kosan_id', $request->properti_id);
                })->latest()->first();
        } else {
            $booking = (clone $bookingQuery)
                ->where('kontrakan_id', $request->properti_id)
                ->latest()->first();
        }

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu harus menyelesaikan pembayaran dulu sebelum memberikan ulasan.',
            ], 403);
        }

        $sudah = Ulasan::where('booking_id', $booking->id)
            ->where(function ($q) use ($request) {
                if ($request->tipe === 'kosan') {
                    $q->where('kosan_id', $request->properti_id);
                } else {
                    $q->where('kontrakan_id', $request->properti_id);
                }
            })
            ->exists();

        if ($sudah) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu sudah memberikan ulasan untuk properti ini.',
            ], 422);
        }

        $ulasan = Ulasan::create([
            'booking_id'     => $booking->id,
            'pencari_kos_id' => $pencariKos->id,
            'kosan_id'       => $request->tipe === 'kosan' ? $request->properti_id : null,
            'kontrakan_id'   => $request->tipe === 'kontrakan' ? $request->properti_id : null,
            'rating'         => $request->rating,
            'komentar'       => $request->komentar,
            'is_anonymous'   => $request->boolean('is_anonymous', false),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil dikirim!',
            'data'    => $ulasan,
        ], 201);
    }

    public function balas(Request $request, $id)
    {
        $request->validate([
            'balasan' => 'required|string|max:500',
        ]);

        $ulasan = Ulasan::findOrFail($id);
        $user   = $request->user();
        $pemilik = $user->pemilikProperti;

        if ($pemilik) {
            $isOwner = false;
            if ($ulasan->kosan_id) {
                $isOwner = $ulasan->kosan?->pemilik_properti_id === $pemilik->id;
            } elseif ($ulasan->kontrakan_id) {
                $isOwner = $ulasan->kontrakan?->pemilik_properti_id === $pemilik->id;
            }
            if (!$isOwner) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
        }

        $ulasan->update(['balasan_pemilik' => $request->balasan]);

        return response()->json([
            'success' => true,
            'message' => 'Balasan berhasil disimpan.',
        ]);
    }

    public function ulasanSaya(Request $request)
    {
        $user = $request->user();
        $pencariKos = $user->pencariKos;

        if (!$pencariKos) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $ulasan = Ulasan::with(['kosan', 'kontrakan'])
            ->where('pencari_kos_id', $pencariKos->id)
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id'              => $item->id,
                    'rating'          => $item->rating,
                    'komentar'        => $item->komentar,
                    'created_at'      => $item->created_at->diffForHumans(),
                    'is_anonymous'    => $item->is_anonymous,
                    'balasan_pemilik' => $item->balasan_pemilik,
                    'properti_nama'   => $item->kosan?->nama_properti
                                        ?? $item->kontrakan?->nama_properti
                                        ?? '-',
                    'properti_tipe'   => $item->kosan_id ? 'Kosan' : 'Kontrakan',
                ];
            });

        return response()->json(['success' => true, 'data' => $ulasan]);
    }

    public function bookingBelumReview(Request $request)
    {
        $user = $request->user();
        $pencariKos = $user->pencariKos;

        if (!$pencariKos) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $bookings = Booking::with(['kamar.tipeKamar.kosan', 'kontrakan'])
            ->where('pencari_kos_id', $pencariKos->id)
            ->where('status_booking', 'lunas')
            ->get()
            ->filter(function ($booking) {
                return !Ulasan::where('booking_id', $booking->id)->exists();
            })
            ->map(function ($booking) {
                $isKosan = $booking->kamar_id !== null;

                if ($isKosan) {
                    $kosan = $booking->kamar?->tipeKamar?->kosan;
                    return [
                        'booking_id'    => $booking->id,
                        'properti_id'   => $kosan?->id,
                        'properti_nama' => $kosan?->nama_properti,
                        'properti_tipe' => 'kosan',
                        'tipe_kamar'    => $booking->kamar?->tipeKamar?->nama_tipe,
                    ];
                }

                return [
                    'booking_id'    => $booking->id,
                    'properti_id'   => $booking->kontrakan_id,
                    'properti_nama' => $booking->kontrakan?->nama_properti,
                    'properti_tipe' => 'kontrakan',
                    'tipe_kamar'    => null,
                ];
            })
            ->filter(fn ($b) => $b['properti_id'] !== null) 
            ->values();

        return response()->json(['success' => true, 'data' => $bookings]);
    }
}