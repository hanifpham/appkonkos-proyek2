<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification as MidtransNotification;
use Throwable;

class MidtransNotificationController extends Controller
{
    public function __invoke(Request $request, MidtransService $midtrans): JsonResponse
    {
        $payload = $request->all();

        try {
            if (! $midtrans->isValidSignature($payload)) {
                Log::warning('Midtrans notification ignored because signature is invalid.', [
                    'order_id' => $payload['order_id'] ?? null,
                    'status_code' => $payload['status_code'] ?? null,
                    'gross_amount' => $payload['gross_amount'] ?? null,
                ]);

                return response()->json([
                    'status' => 'success',
                ], 200);
            }

            $payload = $this->resolveMidtransPayload($request, $payload);
            $payment = $midtrans->handleNotification($payload);

            Log::info('Midtrans notification processed.', [
                'order_id' => $payload['order_id'] ?? null,
                'payment_id' => $payment->id,
                'status_bayar' => $payment->status_bayar,
                'status_midtrans' => $payment->status_midtrans,
            ]);
        } catch (Throwable $exception) {
            Log::error('Midtrans notification failed but acknowledged to Midtrans.', [
                'message' => $exception->getMessage(),
                'order_id' => $payload['order_id'] ?? null,
                'payload' => $payload,
                'exception' => $exception,
            ]);
        }

        return response()->json([
            'status' => 'success',
        ], 200);
    }

    /**
     * @param  array<string, mixed>  $fallbackPayload
     * @return array<string, mixed>
     */
    private function resolveMidtransPayload(Request $request, array $fallbackPayload): array
    {
        if (app()->runningUnitTests() || blank($request->input('transaction_id'))) {
            return $fallbackPayload;
        }

        try {
            $notification = new MidtransNotification();
            $response = $notification->getResponse();
            $notificationPayload = json_decode((string) json_encode($response), true);

            if (! is_array($notificationPayload)) {
                return $fallbackPayload;
            }

            return array_merge($fallbackPayload, $notificationPayload);
        } catch (Throwable $exception) {
            Log::warning('Midtrans SDK notification read failed; falling back to signed request payload.', [
                'message' => $exception->getMessage(),
                'order_id' => $fallbackPayload['order_id'] ?? null,
            ]);

            return $fallbackPayload;
        }
    }
}
