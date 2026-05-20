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

    return response()->json(['success' => true, 'data' => $bookings]);
}
    public function store(Request $request, MidtransService $midtrans)
    {
        $user = $request->user();
        $pencariKos = $user->pencariKos;

        if (!$pencariKos || 
            empty($user->no_telepon) || 
            empty($user->no_wa) || 
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
        ]);

        if (!$request->kamar_id && !$request->kontrakan_id) {
            return response()->json(['success' => false, 'message' => 'Kamar atau kontrakan wajib dipilih'], 422);
        }

        $tglMulai = \Carbon\Carbon::parse($request->tgl_mulai_sewa);
        $tglSelesai = $tglMulai->copy()->addMonths($request->durasi_bulan);
        $totalHarga = 0;
        $biayaLayanan = 10000;
        $totalHarga = $biayaLayanan;

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
        ]);

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
                'biaya_layanan'  => $biayaLayanan,
                'no_wa_pemilik' => $noWaPemilik,
            ], 201);

        } catch (\Exception $e) {
            $booking->delete();
            if (isset($kamar)) {
                $kamar->update(['status_kamar' => 'tersedia']);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi: ' . $e->getMessage(),
            ], 422);
        }
    }

    private function getCekFieldKosong($user, $pencariKos): array
    {
        $kosong = [];
        if (empty($user->no_telepon)) $kosong[] = 'no_telepon';
        if (empty($user->no_wa)) $kosong[] = 'no_wa';
        if (!$pencariKos || empty($pencariKos->kota_asal)) $kosong[] = 'domisili';
        if (!$pencariKos || empty($pencariKos->jenis_kelamin)) $kosong[] = 'jenis_kelamin';
        if (!$pencariKos || empty($pencariKos->pekerjaan)) $kosong[] = 'pekerjaan';
        return $kosong;
    }
    public function cancel(Request $request, $id)
{
    $user = $request->user();
    $booking = Booking::where('id', $id)
        ->where('pencari_kos_id', $user->pencariKos->id)
        ->firstOrFail();

    $booking->update(['status_booking' => 'batal']);

    return response()->json(['success' => true]);
}
}