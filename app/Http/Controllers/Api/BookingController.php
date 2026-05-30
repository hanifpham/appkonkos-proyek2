<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Kontrakan;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
{
    $pencariKos = $request->user()->pencariKos;

    if (!$pencariKos) {
        return response()->json(['success' => false, 'data' => []]);
    }

    $bookings = Booking::where('pencari_kos_id', $pencariKos->id)
        ->with(['kamar.tipeKamar.kosan', 'kontrakan', 'pembayaran'])
        ->latest()
        ->get();

    $data = $bookings->map(function ($booking) {
        $foto = '';
        if ($booking->kamar) {
            $foto = $booking->kamar->tipeKamar?->kosan
                ?->getFirstMediaUrl('foto_properti', 'webp') ?? '';
            $foto = str_replace('http://localhost', 'http://192.168.1.8:8000', $foto);
        } elseif ($booking->kontrakan) {
            $foto = $booking->kontrakan
                ->getFirstMediaUrl('foto_properti', 'webp') ?? '';
            $foto = str_replace('http://localhost', 'http://192.168.1.8:8000', $foto);
        }

        $nama = '';
        if ($booking->kamar) {
            $namaKosan = $booking->kamar->tipeKamar?->kosan?->nama_properti ?? '';
            $namaTipe  = $booking->kamar->tipeKamar?->nama_tipe ?? '';
            $nama = $namaKosan . ($namaTipe ? ' • ' . $namaTipe : '');
        } else {
            $nama = $booking->kontrakan?->nama_properti ?? 'Kontrakan';
        }

        $alamat = '';
        if ($booking->kamar) {
            $alamat = $booking->kamar->tipeKamar?->kosan?->alamat_lengkap ?? '';
        } else {
            $alamat = $booking->kontrakan?->alamat_lengkap ?? '';
        }

        // ← TAMBAH INI: cek pembayaran juga
        $statusBooking = $booking->status_booking;
        $statusBayar   = $booking->pembayaran?->status_bayar;

        if ($statusBayar === 'lunas' && $statusBooking === 'pending') {
            $booking->update(['status_booking' => 'lunas']);
            $statusBooking = 'lunas';
        }

        return [
            'id'             => $booking->id,
            'nama'           => $nama,
            'alamat'         => $alamat,
            'foto'           => $foto,
            'total_biaya'    => $booking->total_biaya,
            'status_booking' => $statusBooking, // ← pakai variabel ini
            'created_at'     => $booking->created_at,
            'kamar_id'       => $booking->kamar_id,
            'kontrakan_id'   => $booking->kontrakan_id,
            'batas_bayar'    => $statusBooking === 'pending'
                ? $booking->created_at->addHours(24)->toIso8601String()
                : null,
            'redirect_url'   => $booking->pembayaran?->snap_redirect_url ?? '',
        ];
    });

    return response()->json(['success' => true, 'data' => $data]);
}

    public function store(Request $request, MidtransService $midtrans)
    {
        $user = $request->user();
        $pencariKos = $user->pencariKos;

        if (!$pencariKos ||
            empty($user->no_telepon) ||
            empty($pencariKos->kota_asal) ||
            empty($pencariKos->jenis_kelamin) ||
            empty($pencariKos->pekerjaan)) {
            return response()->json([
                'success' => false,
                'message' => 'profil_tidak_lengkap',
                'field_kosong' => $this->getCekFieldKosong($user, $pencariKos),
            ], 422);
        }

        $request->validate([
            'kamar_id'       => 'nullable|exists:kamar,id',
            'kontrakan_id'   => 'nullable|exists:kontrakan,id',
            'tgl_mulai_sewa' => 'required|date',
            'durasi_bulan'   => 'required|integer|min:1',
            'catatan'        => 'nullable|string|max:500',
        ]);

        if (!$request->kamar_id && !$request->kontrakan_id) {
            return response()->json(['success' => false, 'message' => 'Kamar atau kontrakan wajib dipilih'], 422);
        }

        $tglMulai     = \Carbon\Carbon::parse($request->tgl_mulai_sewa);
        $tglSelesai   = $tglMulai->copy()->addMonths($request->durasi_bulan);
        $biayaLayanan = 10000;
        $totalHarga   = $biayaLayanan;

        if ($request->kamar_id) {
            $kamar = Kamar::with('tipeKamar')->findOrFail($request->kamar_id);

            if ($kamar->status_kamar !== 'tersedia') {
                return response()->json([
                    'success' => false,
                    'message' => 'Kamar sudah tidak tersedia'
                ], 422);
            }

            $totalHarga += $kamar->tipeKamar->harga_per_bulan * $request->durasi_bulan;

        } else {
            $kontrakan = Kontrakan::findOrFail($request->kontrakan_id);

            if ($kontrakan->sisa_kamar <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kontrakan sudah tidak tersedia'
                ], 422);
            }

            $totalHarga += $kontrakan->harga_sewa_tahun * ceil($request->durasi_bulan / 12);
        }

        $booking = Booking::create([
            'pencari_kos_id'   => $pencariKos->id,
            'kamar_id'         => $request->kamar_id,
            'kontrakan_id'     => $request->kontrakan_id,
            'tgl_mulai_sewa'   => $tglMulai,
            'tgl_selesai_sewa' => $tglSelesai,
            'durasi_bulan'     => $request->durasi_bulan,
            'total_biaya'      => $totalHarga,
            'status_booking'   => 'pending',
            'catatan'          => $request->catatan ?? '',
        ]);

        if ($request->kamar_id) {
            $kamar->update(['status_kamar' => 'dihuni']);
        } else {
            $kontrakan->decrement('sisa_kamar');
        }

        try {
            $payment = $midtrans->createOrRefreshTransaction($booking);
            $redirectUrl = $payment->snap_redirect_url;
            if (empty($redirectUrl) && !empty($payment->snap_token)) {
                $redirectUrl = 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $payment->snap_token;
            }

            $noWaPemilik = '';
            if ($booking->kamar) {
                $noWaPemilik = optional(optional(optional(optional($booking->kamar->tipeKamar)->kosan)->pemilikProperti)->user)->no_wa ?? '';
            } elseif ($booking->kontrakan) {
                $noWaPemilik = optional(optional(optional($booking->kontrakan->pemilikProperti)->user))->no_wa ?? '';
            }

            return response()->json([
                'success'       => true,
                'booking_id'    => $booking->id,
                'snap_token'    => $payment->snap_token,
                'redirect_url'  => $redirectUrl,
                'total_harga'   => $totalHarga,
                'biaya_layanan' => $biayaLayanan,
                'no_wa_pemilik' => $noWaPemilik,
            ], 201);

        } catch (\Exception $e) {
            $booking->delete();

            if (isset($kamar)) {
                $kamar->update(['status_kamar' => 'tersedia']);
            } elseif (isset($kontrakan)) {
                $kontrakan->increment('sisa_kamar');
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function cancel(Request $request, $id)
    {
        $booking = Booking::where('id', $id)
            ->where('pencari_kos_id', $request->user()->pencariKos->id)
            ->firstOrFail();

        $booking->update(['status_booking' => 'batal']);

        if ($booking->kamar_id) {
            Kamar::where('id', $booking->kamar_id)
                ->update(['status_kamar' => 'tersedia']);
        } elseif ($booking->kontrakan_id) {
            Kontrakan::where('id', $booking->kontrakan_id)
                ->increment('sisa_kamar');
        }

        return response()->json(['success' => true]);
    }

    public function destroy(Request $request, $id)
    {
        $booking = Booking::where('id', $id)
            ->where('pencari_kos_id', $request->user()->pencariKos->id)
            ->firstOrFail();

        if (!in_array($booking->status_booking, ['batal', 'refund'])) {
            return response()->json([
                'success' => false,
                'message' => 'Booking aktif tidak bisa dihapus'
            ], 422);
        }

        $booking->delete();

        return response()->json(['success' => true]);
    }

    private function getCekFieldKosong($user, $pencariKos): array
    {
        $kosong = [];
        if (empty($user->no_telepon)) $kosong[] = 'no_telepon';
        if (!$pencariKos || empty($pencariKos->kota_asal)) $kosong[] = 'domisili';
        if (!$pencariKos || empty($pencariKos->jenis_kelamin)) $kosong[] = 'jenis_kelamin';
        if (!$pencariKos || empty($pencariKos->pekerjaan)) $kosong[] = 'pekerjaan';
        return $kosong;
    }
}