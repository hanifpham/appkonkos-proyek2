<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;

class PaymentCallbackController extends Controller
{
    public function receive(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Cari transaksi (pembayaran) berdasarkan order_id dari Midtrans
        $pembayaran = Pembayaran::where('midtrans_order_id', $request->order_id)
            ->with(['booking.kamar', 'booking.kontrakan'])
            ->first();

        if (!$pembayaran) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $status = $request->transaction_status;

        // Update status_bayar dan payload
        $pembayaran->update([
            'status_bayar' => $status,
            'status_midtrans' => $status,
            'payload_midtrans' => $request->all(),
        ]);

        if (in_array($status, ['capture', 'settlement'])) {
            $booking = $pembayaran->booking;
            
            if ($booking) {
                // Update status booking
                $booking->update(['status_booking' => 'lunas']);
                \Illuminate\Support\Facades\Log::info("Booking {$booking->id} (Order Midtrans {$request->order_id}) berhasil lunas. Status booking diubah menjadi lunas.");

                // ----------------------------------------------------
                // LOGIKA GEMBOK KAMAR UNTUK KOSAN (Menggunakan status_kamar)
                // ----------------------------------------------------
                if ($booking->kamar) {
                    \Illuminate\Support\Facades\Log::info("Kamar Kosan ditemukan: ID {$booking->kamar->id}. Status awal: " . $booking->kamar->status_kamar);
                    
                    if ($booking->kamar->status_kamar === 'tersedia') {
                        $booking->kamar->update(['status_kamar' => 'dihuni']);
                        \Illuminate\Support\Facades\Log::info("Kamar ID {$booking->kamar->id} otomatis digembok (status_kamar menjadi 'dihuni').");
                    } else {
                        \Illuminate\Support\Facades\Log::warning("Kamar ID {$booking->kamar->id} tidak digembok karena statusnya sudah bukan 'tersedia' (Status saat ini: {$booking->kamar->status_kamar}).");
                    }
                } 
                // ----------------------------------------------------
                // LOGIKA KURANGI STOK UNTUK KONTRAKAN (Menggunakan sisa_kamar)
                // ----------------------------------------------------
                elseif ($booking->kontrakan) {
                    \Illuminate\Support\Facades\Log::info("Kontrakan ditemukan: ID {$booking->kontrakan->id}. Sisa kamar (stok) awal: " . $booking->kontrakan->sisa_kamar);
                    
                    if ($booking->kontrakan->sisa_kamar > 0) {
                        $booking->kontrakan->decrement('sisa_kamar');
                        $stokBaru = $booking->kontrakan->fresh()->sisa_kamar;
                        \Illuminate\Support\Facades\Log::info("Stok kontrakan setelah dikurangi: " . $stokBaru);
                        
                        // Jika setelah dikurangi stoknya jadi 0, gembok kamarnya (nonaktif)
                        if ($stokBaru == 0) {
                            $booking->kontrakan->update(['status' => 'nonaktif']);
                            \Illuminate\Support\Facades\Log::info("Kontrakan ID {$booking->kontrakan->id} otomatis dinonaktifkan karena sisa_kamar sudah 0.");
                        }
                    } else {
                        \Illuminate\Support\Facades\Log::warning("Stok kontrakan ID {$booking->kontrakan->id} tidak dikurangi karena sisa_kamar sudah 0 atau kurang.");
                    }
                } else {
                    \Illuminate\Support\Facades\Log::error("KRITIKAL: Relasi kamar/kontrakan tidak ditemukan untuk Booking {$booking->id} (Order Midtrans {$request->order_id})!");
                }
            } else {
                \Illuminate\Support\Facades\Log::error("KRITIKAL: Booking tidak ditemukan untuk Pembayaran dengan Order Midtrans {$request->order_id}!");
            }
        } elseif (in_array($status, ['cancel', 'deny', 'expire', 'failure'])) {
            $booking = $pembayaran->booking;
            
            if ($booking) {
                $booking->update(['status_booking' => 'batal']);
                \Illuminate\Support\Facades\Log::info("Pembayaran Order {$request->order_id} gagal/batal. Status booking {$booking->id} diubah menjadi batal.");
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Notifikasi berhasil diproses'], 200);
    }
}
