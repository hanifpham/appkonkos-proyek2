<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use RuntimeException;

class MidtransService
{
    public function __construct()
    {
        $this->configure();
    }

    public function createOrRefreshTransaction(Booking $booking): Pembayaran
    {
        $this->ensureConfigured();

        $booking->loadMissing([
            'pencariKos.user',
            'kamar.tipeKamar.kosan',
            'kontrakan',
            'pembayaran',
        ]);

        $payment = $booking->pembayaran()->firstOrNew();

        if ($payment->exists && $payment->isSuccessful()) {
            return $payment;
        }

        if ($payment->exists && $payment->isPending() && filled($payment->snap_token)) {
            return $payment;
        }

        $orderId = $this->generateOrderId($booking);
        $transaction = Snap::createTransaction($this->buildTransactionPayload($booking, $orderId));

        $payment->booking_id = $booking->id;
        $payment->nominal_bayar = (int) $booking->total_biaya;
        $payment->status_bayar = 'pending';
        $payment->status_midtrans = 'pending';
        $payment->midtrans_order_id = $orderId;
        $payment->snap_token = (string) ($transaction->token ?? '');
        $payment->snap_redirect_url = (string) ($transaction->redirect_url ?? '');
        $payment->payload_midtrans = [
            'snap_token' => $payment->snap_token,
            'snap_redirect_url' => $payment->snap_redirect_url,
        ];
        $payment->save();

        return $payment->fresh();
    }

    public function handleNotification(array $payload): Pembayaran
    {
        $orderId = (string) Arr::get($payload, 'order_id', '');

        if ($orderId === '') {
            throw new RuntimeException('Payload Midtrans tidak memiliki order_id.');
        }

        $payment = Pembayaran::query()
            ->where('midtrans_order_id', $orderId)
            ->first();

        if ($payment === null) {
            throw (new ModelNotFoundException())->setModel(Pembayaran::class, [$orderId]);
        }

        return $this->syncPaymentStatus($payment, $payload);
    }

    public function syncPaymentStatus(Pembayaran $payment, array $payload): Pembayaran
    {
        $transactionStatus = strtolower((string) Arr::get($payload, 'transaction_status', ''));
        $fraudStatus = strtolower((string) Arr::get($payload, 'fraud_status', ''));
        $paymentMethod = $this->resolvePaymentMethod($payload);
        $normalizedStatus = $this->normalizeTransactionStatus($transactionStatus, $fraudStatus);

        $payment->fill([
            'status_bayar' => $normalizedStatus,
            'status_midtrans' => $transactionStatus !== '' ? $transactionStatus : null,
            'midtrans_transaction_id' => Arr::get($payload, 'transaction_id'),
            'fraud_status' => $fraudStatus !== '' ? $fraudStatus : null,
            'metode_bayar' => $paymentMethod,
            'url_struk_pdf' => Arr::get($payload, 'pdf_url', $payment->url_struk_pdf),
            'payload_midtrans' => $payload,
        ]);

        if ($payment->isSuccessful()) {
            $payment->waktu_bayar = $this->resolveTransactionTime($payload) ?? now();
        }

        $payment->save();

        return $payment->fresh();
    }

    public function isValidSignature(array $payload): bool
    {
        $signatureKey = (string) Arr::get($payload, 'signature_key', '');
        $orderId = (string) Arr::get($payload, 'order_id', '');
        $statusCode = (string) Arr::get($payload, 'status_code', '');
        $grossAmount = (string) Arr::get($payload, 'gross_amount', '');

        if ($signatureKey === '' || $orderId === '' || $statusCode === '' || $grossAmount === '') {
            return false;
        }

        $expected = hash(
            'sha512',
            $orderId.$statusCode.$grossAmount.(string) config('services.midtrans.server_key')
        );

        return hash_equals($expected, $signatureKey);
    }

    public function getSnapScriptUrl(): string
    {
        return $this->isProduction()
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    public function getClientKey(): string
    {
        return (string) config('services.midtrans.client_key');
    }

    protected function configure(): void
    {
        MidtransConfig::$serverKey = (string) config('services.midtrans.server_key');
        MidtransConfig::$isProduction = $this->isProduction();
        MidtransConfig::$isSanitized = (bool) config('services.midtrans.is_sanitized', true);
        MidtransConfig::$is3ds = (bool) config('services.midtrans.is_3ds', true);
    }

    protected function ensureConfigured(): void
    {
        if (
            blank(config('services.midtrans.server_key'))
            || blank(config('services.midtrans.client_key'))
            || blank(config('services.midtrans.merchant_id'))
        ) {
            throw new RuntimeException('Konfigurasi Midtrans belum lengkap.');
        }
    }

    protected function isProduction(): bool
    {
        return (bool) config('services.midtrans.is_production', false);
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildTransactionPayload(Booking $booking, string $orderId): array
    {
        $customer = $booking->pencariKos?->user;

        return [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $booking->total_biaya,
            ],
            'customer_details' => [
                'first_name' => $customer?->name ?? 'Penyewa',
                'email' => $customer?->email ?? 'penyewa@appkonkos.test',
                'phone' => $customer?->no_telepon ?? '',
            ],
            'item_details' => [
                [
                    'id' => (string) $booking->id,
                    'price' => (int) $booking->total_biaya,
                    'quantity' => 1,
                    'name' => $this->resolvePropertyLabel($booking),
                ],
            ],
        ];
    }

    protected function generateOrderId(Booking $booking): string
    {
        return 'APPKONKOS-'.$booking->id.'-'.now()->format('YmdHis');
    }

    protected function resolvePropertyLabel(Booking $booking): string
    {
        if ($booking->kamar !== null) {
            $kosan = $booking->kamar->tipeKamar?->kosan?->nama_properti ?? 'Kosan';
            $nomorKamar = $booking->kamar->nomor_kamar ?? '-';

            return sprintf('%s - Kamar %s', $kosan, $nomorKamar);
        }

        return $booking->kontrakan?->nama_properti ?? 'Kontrakan';
    }

    protected function normalizeTransactionStatus(string $transactionStatus, string $fraudStatus): string
    {
        return match (true) {
            $transactionStatus === 'capture' && $fraudStatus === 'challenge' => 'pending',
            in_array($transactionStatus, ['capture', 'settlement'], true) => 'lunas',
            in_array($transactionStatus, ['pending', 'authorize', 'challenge'], true) => 'pending',
            in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'], true) => 'gagal',
            in_array($transactionStatus, Pembayaran::REFUND_STATUSES, true) => 'refund',
            default => $transactionStatus !== '' ? $transactionStatus : 'pending',
        };
    }

    protected function resolvePaymentMethod(array $payload): ?string
    {
        $paymentType = strtolower((string) Arr::get($payload, 'payment_type', ''));

        if ($paymentType === '') {
            return null;
        }

        if ($paymentType === 'bank_transfer') {
            $bank = strtolower((string) Arr::get($payload, 'va_numbers.0.bank', Arr::get($payload, 'bank', '')));

            if ($bank === '' && filled(Arr::get($payload, 'permata_va_number'))) {
                $bank = 'permata';
            }

            return $bank !== '' ? sprintf('%s_%s', $paymentType, $bank) : $paymentType;
        }

        return $paymentType;
    }

    protected function resolveTransactionTime(array $payload): ?Carbon
    {
        $value = Arr::get($payload, 'settlement_time', Arr::get($payload, 'transaction_time'));

        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }
}
