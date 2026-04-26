<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pencari;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class PembayaranController extends Controller
{
    public function show(Request $request, Booking $booking, MidtransService $midtrans): View
    {
        $booking = $this->ownedBooking($request, $booking);

        $booking->load([
            'pencariKos.user',
            'pembayaran',
            'kamar.tipeKamar.kosan.pemilikProperti.user',
            'kontrakan.pemilikProperti.user',
        ]);

        return view('pencari.pembayaran.show', [
            'booking' => $booking,
            'snapScriptUrl' => $midtrans->getSnapScriptUrl(),
            'midtransClientKey' => $midtrans->getClientKey(),
            'propertyName' => $this->propertyName($booking),
            'ownerName' => $this->ownerName($booking),
            'periodLabel' => $booking->tgl_mulai_sewa?->format('d M Y').' - '.$booking->tgl_selesai_sewa?->format('d M Y'),
        ]);
    }

    public function snapToken(Request $request, Booking $booking, MidtransService $midtrans): JsonResponse
    {
        $booking = $this->ownedBooking($request, $booking);

        try {
            $payment = $midtrans->createOrRefreshTransaction($booking);
        } catch (RuntimeException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'snap_token' => $payment->snap_token,
            'redirect_url' => $payment->snap_redirect_url,
            'order_id' => $payment->midtrans_order_id,
            'status_bayar' => $payment->status_bayar,
        ]);
    }

    protected function ownedBooking(Request $request, Booking $booking): Booking
    {
        /** @var User|null $user */
        $user = $request->user();
        $pencariKos = $user?->pencariKos;

        abort_if($pencariKos === null || $booking->pencari_kos_id !== $pencariKos->id, 404);

        return $booking;
    }

    protected function propertyName(Booking $booking): string
    {
        if ($booking->kamar !== null) {
            $kosan = $booking->kamar->tipeKamar?->kosan?->nama_properti ?? 'Kosan';
            $nomorKamar = $booking->kamar->nomor_kamar ?? '-';

            return sprintf('%s - Kamar %s', $kosan, $nomorKamar);
        }

        return $booking->kontrakan?->nama_properti ?? 'Kontrakan';
    }

    protected function ownerName(Booking $booking): string
    {
        if ($booking->kamar !== null) {
            return $booking->kamar->tipeKamar?->kosan?->pemilikProperti?->user?->name ?? 'Pemilik properti';
        }

        return $booking->kontrakan?->pemilikProperti?->user?->name ?? 'Pemilik properti';
    }
}
