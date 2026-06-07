<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Kontrakan;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Transaction;
use Midtrans\Config as MidtransConfig;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $pencariKos = $request->user()->pencariKos;

        if (!$pencariKos) {
            return response()->json(['success' => false, 'data' => []]);
        }

        $bookings = Booking::where('pencari_kos_id', $pencariKos->id)
            ->with(['kamar.tipeKamar.kosan', 'kontrakan', 'pembayaran', 'refund'])
            ->latest()
            ->get();

        $data = $bookings->map(function ($booking) {
            $foto = '';
            if ($booking->kamar) {
                $foto = $booking->kamar->tipeKamar?->kosan
                    ?->getFirstMediaUrl('foto_properti', 'webp') ?? '';
                $foto = str_replace('http://localhost',  config('app.url'), $foto);
            } elseif ($booking->kontrakan) {
                $foto = $booking->kontrakan
                    ->getFirstMediaUrl('foto_properti', 'webp') ?? '';
                $foto = str_replace('http://localhost',  config('app.url'), $foto);
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

            $statusBooking = $booking->status_booking;
            $statusBayar   = $booking->pembayaran?->status_bayar;

            if ($statusBayar === 'lunas' && $statusBooking === 'pending') {
                $booking->update(['status_booking' => 'lunas']);
                $statusBooking = 'lunas';
            }

            if ($statusBayar === 'pending' && $statusBooking === 'pending') {
                $orderId = $booking->pembayaran?->midtrans_order_id;
                if ($orderId) {
                    try {
                        MidtransConfig::$serverKey = config('services.midtrans.server_key');
                        MidtransConfig::$isProduction = false;
                        $status = Transaction::status($orderId);
                        $transactionStatus = null;
                        if (is_object($status)) {
                            $transactionStatus = $status->transaction_status ?? null;
                        } elseif (is_array($status)) {
                            $transactionStatus = $status['transaction_status'] ?? null;
                        }
                        if (in_array($transactionStatus, ['settlement', 'capture'], true)) {
                            $booking->pembayaran->update(['status_bayar' => 'lunas']);
                            $booking->update(['status_booking' => 'lunas']);
                            $statusBooking = 'lunas';
                        }
                    } catch (\Exception $e) {
                    }
                }
            }

            $noWaPemilik = '';
            if ($booking->kamar) {
                $noWaPemilik = optional(
                    optional(optional(optional($booking->kamar->tipeKamar)->kosan)->pemilikProperti)->user
                )->no_wa ?? '';
            } elseif ($booking->kontrakan) {
                $noWaPemilik = optional(
                    optional($booking->kontrakan->pemilikProperti)->user
                )->no_wa ?? '';
            }

            $refund = $booking->refund;

            return [
                'id'             => $booking->id,
                'nama'           => $nama,
                'alamat'         => $alamat,
                'foto'           => $foto,
                'total_biaya'    => $booking->total_biaya,
                'status_booking' => $statusBooking,
                'created_at'     => $booking->created_at,
                'check_in'       => $booking->tgl_mulai_sewa,
                'check_out'      => $booking->tgl_selesai_sewa,
                'kamar_id'       => $booking->kamar_id,
                'kontrakan_id'   => $booking->kontrakan_id,
                'batas_bayar'    => $statusBooking === 'pending'
                    ? $booking->created_at->addHours(24)->toIso8601String()
                    : null,
                'redirect_url'   => $booking->pembayaran?->snap_redirect_url ?? '',
                'no_wa_pemilik'  => $noWaPemilik,
                'tipe_property'  => $booking->kontrakan_id ? 'Kontrakan' : 'Kos',
                'refund_status'  => $refund?->status_refund,
                'alasan_refund'  => $refund?->alasan_refund,
                'nominal_refund' => $refund?->nominal_refund,
                'bukti_transfer' => $refund?->bukti_transfer_refund
                    ? str_replace('http://localhost',  config('app.url'), $refund->bukti_transfer_refund)
                    : null,
                'kamar_nama'     => $booking->kamar?->nomor_kamar ?? '',
                'tipe_kamar'     => $booking->kamar?->tipeKamar?->nama_tipe ?? '',
                'gender'         => $booking->kamar?->tipeKamar?->kosan?->jenis_kelamin ?? '',
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
                'success'      => false,
                'message'      => 'profil_tidak_lengkap',
                'field_kosong' => $this->getCekFieldKosong($user, $pencariKos),
            ], 422);
        }

        $request->validate([
            'kamar_id'       => 'nullable|exists:kamar,id',
            'kontrakan_id'   => 'nullable|exists:kontrakan,id',
            'tgl_mulai_sewa' => 'required|date',
        ]);

        if (!$request->kamar_id && !$request->kontrakan_id) {
            return response()->json([
                'success' => false,
                'message' => 'Kamar atau kontrakan wajib dipilih',
            ], 422);
        }

        $tglMulai = \Carbon\Carbon::parse($request->tgl_mulai_sewa);
        $tglSelesai = $request->kamar_id
            ? $tglMulai->copy()->addMonths($request->durasi_bulan)
            : $tglMulai->copy()->addYears($request->durasi_bulan);

        $biayaLayanan = 10000;
        $booking      = null;
        $kamar        = null;
        $kontrakan    = null;
        $totalHarga   = 0;

        try {
            // ── Bungkus dalam transaction + pessimistic lock ──────────────
            DB::transaction(function () use (
                $request, $pencariKos, $tglMulai, $tglSelesai,
                $biayaLayanan, &$booking, &$kamar, &$kontrakan, &$totalHarga
            ) {
                $totalHarga = $biayaLayanan;

                if ($request->kamar_id) {
                    $kamar = Kamar::with('tipeKamar')
                        ->lockForUpdate()
                        ->findOrFail($request->kamar_id);

                    // Cek ulang status setelah dapat lock
                    if ($kamar->status_kamar !== 'tersedia') {
                        throw new \Exception('kamar_tidak_tersedia');
                    }

                    $totalHarga += $kamar->tipeKamar->harga_per_bulan * $request->durasi_bulan;

                    // Langsung ubah status sebelum booking dibuat
                    $kamar->update(['status_kamar' => 'dihuni']);

                } else {
                    // lockForUpdate() pada kontrakan
                    $kontrakan = Kontrakan::lockForUpdate()
                        ->findOrFail($request->kontrakan_id);

                    if ($kontrakan->sisa_kamar <= 0) {
                        throw new \Exception('kontrakan_tidak_tersedia');
                    }

                    $totalHarga += $kontrakan->harga_sewa_tahun * $request->durasi_bulan;

                    $kontrakan->decrement('sisa_kamar');
                }

                $booking = Booking::create([
                    'pencari_kos_id'   => $pencariKos->id,
                    'kamar_id'         => $request->kamar_id,
                    'kontrakan_id'     => $request->kontrakan_id,
                    'tgl_mulai_sewa'   => $tglMulai,
                    'tgl_selesai_sewa' => $tglSelesai,
                    'total_biaya'      => $totalHarga,
                    'status_booking'   => 'pending',
                ]);
            });

        } catch (\Exception $e) {
            // Rollback otomatis oleh DB::transaction
            $msg = match ($e->getMessage()) {
                'kamar_tidak_tersedia'     => 'Maaf, kamar baru saja dipesan orang lain.',
                'kontrakan_tidak_tersedia' => 'Maaf, kontrakan sudah penuh.',
                default                    => 'Gagal membuat booking: ' . $e->getMessage(),
            };

            return response()->json(['success' => false, 'message' => $msg], 422);
        }

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat booking.',
            ], 500);
        }

        // ── Buat transaksi Midtrans di luar transaction DB ────────────────
        try {
            $payment     = $midtrans->createOrRefreshTransaction($booking);
            $redirectUrl = $payment?->snap_redirect_url ?? '';

            if (empty($redirectUrl) && !empty($payment?->snap_token)) {
                $redirectUrl = 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $payment->snap_token;
            }

            $noWaPemilik = '';
            if ($booking->kamar) {
                $noWaPemilik = optional(
                    optional(optional(optional($booking->kamar->tipeKamar)->kosan)->pemilikProperti)->user
                )->no_wa ?? '';
            } elseif ($booking->kontrakan) {
                $noWaPemilik = optional(
                    optional(optional($booking->kontrakan)->pemilikProperti)->user
                )->no_wa ?? '';
            }

            return response()->json([
                'success'       => true,
                'booking_id'    => $booking->id,
                'snap_token'    => $payment->snap_token,
                'redirect_url'  => $redirectUrl,
                'total_harga'   => $booking->total_biaya,
                'biaya_layanan' => $biayaLayanan,
                'no_wa_pemilik' => $noWaPemilik,
            ], 201);

        } catch (\Exception $e) {
            // Midtrans gagal → batalkan booking & kembalikan stok
            $booking->delete();

            if ($kamar) {
                $kamar->update(['status_kamar' => 'tersedia']);
            } elseif ($kontrakan) {
                $kontrakan->increment('sisa_kamar');
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi pembayaran: ' . $e->getMessage(),
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
                'message' => 'Booking aktif tidak bisa dihapus',
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

    public function refund(Request $request, $id)
    {
        $request->validate([
            'alasan_refund' => 'required|string|min:10|max:500',
        ]);

        $booking = Booking::where('id', $id)
            ->where('pencari_kos_id', $request->user()->pencariKos->id)
            ->firstOrFail();

        if ($booking->status_booking !== 'lunas') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya booking yang sudah dibayar yang bisa direfund',
            ], 422);
        }

        $existing = \App\Models\Refund::where('booking_id', $id)->first();
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Refund sudah pernah diajukan',
            ], 422);
        }

        \App\Models\Refund::create([
            'booking_id'     => $booking->id,
            'pembayaran_id'  => $booking->pembayaran->id,
            'nominal_refund' => $booking->total_biaya,
            'alasan_refund'  => $request->alasan_refund,
            'status_refund'  => 'pending',
        ]);

        $booking->update(['status_booking' => 'refund']);

        if ($booking->kamar_id) {
            Kamar::where('id', $booking->kamar_id)
                ->update(['status_kamar' => 'tersedia']);
        } elseif ($booking->kontrakan_id) {
            Kontrakan::where('id', $booking->kontrakan_id)
                ->increment('sisa_kamar');
        }

        return response()->json(['success' => true, 'message' => 'Pengajuan refund berhasil']);
    }
}