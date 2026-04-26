<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\MidtransService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class MidtransNotificationController extends Controller
{
    public function __invoke(Request $request, MidtransService $midtrans): JsonResponse
    {
        $payload = $request->all();

        if (! $midtrans->isValidSignature($payload)) {
            return response()->json([
                'message' => 'Signature key Midtrans tidak valid.',
            ], 403);
        }

        try {
            $payment = $midtrans->handleNotification($payload);
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Order Midtrans tidak ditemukan.',
            ], 404);
        } catch (RuntimeException $exception) {
            Log::warning('Midtrans notification rejected.', [
                'message' => $exception->getMessage(),
                'payload' => $payload,
            ]);

            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => 'Notification Midtrans diproses.',
            'payment_id' => $payment->id,
            'status_bayar' => $payment->status_bayar,
        ]);
    }
}
