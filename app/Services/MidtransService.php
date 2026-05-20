<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\StrukPembayaranMail;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Kontrakan;
use App\Models\Pembayaran;
use App\Notifications\BookingSettlementNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use RuntimeException;
use Throwable;

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
            throw (new ModelNotFoundException)->setModel(Pembayaran::class, [$orderId]);
        }

        return $this->syncPaymentStatus($payment, $payload);
    }

    public function syncPaymentStatus(Pembayaran $payment, array $payload): Pembayaran
    {
        $transactionStatus = strtolower((string) Arr::get($payload, 'transaction_status', ''));
        $fraudStatus = strtolower((string) Arr::get($payload, 'fraud_status', ''));
        $paymentMethod = $this->resolvePaymentMethod($payload);
        $normalizedStatus = $this->normalizeTransactionStatus($transactionStatus, $fraudStatus);
        $shouldDispatchSuccessNotifications = false;

        DB::beginTransaction();

        try {
            $lockedPayment = Pembayaran::query()
                ->whereKey($payment->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $wasSuccessful = $lockedPayment->isSuccessful();

            $lockedPayment->fill([
                'status_bayar' => $normalizedStatus,
                'status_midtrans' => $transactionStatus !== '' ? $transactionStatus : null,
                'midtrans_transaction_id' => Arr::get($payload, 'transaction_id'),
                'fraud_status' => $fraudStatus !== '' ? $fraudStatus : null,
                'metode_bayar' => $paymentMethod,
                'url_struk_pdf' => Arr::get($payload, 'pdf_url', $lockedPayment->url_struk_pdf),
                'payload_midtrans' => $payload,
            ]);

            if ($lockedPayment->isSuccessful()) {
                $lockedPayment->waktu_bayar = $this->resolveTransactionTime($payload) ?? now();
            }

            Pembayaran::withoutEvents(function () use ($lockedPayment): void {
                $lockedPayment->save();
            });

            if ($lockedPayment->isSuccessful()) {
                $bookingWasPaid = $this->markBookingAndPropertyAsPaid($lockedPayment);
                $shouldDispatchSuccessNotifications = ! $wasSuccessful || ! $bookingWasPaid;
            }

            $payment = $lockedPayment->fresh([
                'booking.pencariKos.user',
                'booking.kamar.tipeKamar.kosan.pemilikProperti.user',
                'booking.kontrakan.pemilikProperti.user',
            ]);

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }

        if ($shouldDispatchSuccessNotifications) {
            $this->dispatchPaymentSuccessNotifications($payment);
        }

        return $payment;
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
        return 'AK-'.str_replace('-', '', (string) $booking->id).'-'.now()->format('YmdHis');
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
        } catch (Throwable) {
            return null;
        }
    }

    protected function markBookingAndPropertyAsPaid(Pembayaran $payment): bool
    {
        $booking = Booking::query()
            ->whereKey($payment->booking_id)
            ->lockForUpdate()
            ->first();

        if ($booking === null) {
            return false;
        }

        $bookingWasPaid = $booking->status_booking === 'lunas';

        if (! $bookingWasPaid) {
            $booking->update([
                'status_booking' => 'lunas',
            ]);
        }

        if ($booking->kamar_id !== null) {
            $kamar = Kamar::query()
                ->whereKey($booking->kamar_id)
                ->lockForUpdate()
                ->first();

            if ($kamar !== null) {
                $updates = [
                    'status_kamar' => 'dihuni',
                ];

                if ($this->hasColumn($kamar->getTable(), 'is_available')) {
                    $updates['is_available'] = false;
                }

                $kamar->forceFill($updates)->save();
                $this->syncRoomParentAvailability($kamar);
            }
        }

        if ($booking->kontrakan_id !== null && ! $bookingWasPaid) {
            $kontrakan = Kontrakan::query()
                ->whereKey($booking->kontrakan_id)
                ->lockForUpdate()
                ->first();

            if ($kontrakan !== null && (int) $kontrakan->sisa_kamar > 0) {
                $remainingRooms = max(0, (int) $kontrakan->sisa_kamar - 1);
                $updates = [
                    'sisa_kamar' => $remainingRooms,
                ];

                if ($this->hasColumn($kontrakan->getTable(), 'is_available')) {
                    $updates['is_available'] = $remainingRooms > 0;
                }

                $kontrakan->forceFill($updates)->save();
            }
        }

        return $bookingWasPaid;
    }

    protected function dispatchPaymentSuccessNotifications(Pembayaran $payment): void
    {
        $booking = $payment->booking;

        if ($booking === null) {
            return;
        }

        $booking->loadMissing([
            'pembayaran',
            'pencariKos.user',
            'kamar.tipeKamar.kosan.pemilikProperti.user',
            'kontrakan.pemilikProperti.user',
        ]);

        $owner = $booking->kamar?->tipeKamar?->kosan?->pemilikProperti?->user
            ?? $booking->kontrakan?->pemilikProperti?->user;
        $tenant = $booking->pencariKos?->user;
        $propertyName = $booking->kamar?->tipeKamar?->kosan?->nama_properti
            ?? $booking->kontrakan?->nama_properti
            ?? 'Properti';
        $roomNumberText = $booking->kamar !== null
            ? '(Kamar: '.$booking->kamar->nomor_kamar.')'
            : '';
        $amount = number_format((float) $payment->nominal_bayar, 0, ',', '.');

        try {
            if ($owner !== null) {
                $owner->notify(new BookingSettlementNotification($booking));
            }
        } catch (Throwable $exception) {
            Log::error('Notifikasi database pembayaran sukses gagal: '.$exception->getMessage(), [
                'payment_id' => $payment->id,
                'booking_id' => $booking->id,
            ]);
        }

        try {
            if ($tenant?->email !== null) {
                Mail::to($tenant->email)
                    ->send(new StrukPembayaranMail($booking, $payment));
            }
        } catch (Throwable $exception) {
            Log::error('Email struk pembayaran sukses gagal: '.$exception->getMessage(), [
                'payment_id' => $payment->id,
                'booking_id' => $booking->id,
                'recipient' => $tenant?->email,
            ]);
        }

        try {
            if ($owner?->email !== null) {
                Mail::raw(
                    "Pembayaran booking untuk {$propertyName} {$roomNumberText} sebesar Rp {$amount} sudah berhasil. Silakan cek Dashboard Mitra untuk detail pesanan.",
                    function ($message) use ($owner): void {
                        $message->to($owner->email)
                            ->subject('Booking APPKONKOS Sudah Dibayar');
                    }
                );
            }
        } catch (Throwable $exception) {
            Log::error('Email pemilik pembayaran sukses gagal: '.$exception->getMessage(), [
                'payment_id' => $payment->id,
                'booking_id' => $booking->id,
                'recipient' => $owner?->email,
            ]);
        }

        try {
            if ($owner !== null && ! empty($owner->no_telepon)) {
                $tenantName = $tenant?->name ?? 'Penyewa';
                $duration = max(1, (int) Carbon::parse($booking->tgl_mulai_sewa)->diffInMonths($booking->tgl_selesai_sewa));
                $message = "Notifikasi APPKONKOS\n\nSelamat! Properti Anda {$propertyName} {$roomNumberText} baru saja disewa oleh {$tenantName} selama {$duration} bulan.\n\nTotal Pendapatan: Rp {$amount}\n\nMohon bersiap untuk menyambut penyewa. Cek selengkapnya di Dashboard Mitra.";

                WhatsAppService::send($owner->no_telepon, $message);
            }

            if ($tenant !== null && ! empty($tenant->no_telepon)) {
                $message = "Notifikasi APPKONKOS\n\nHalo {$tenant->name}, pembayaran sebesar Rp {$amount} telah berhasil!\n\nKamar Anda di {$propertyName} {$roomNumberText} sudah dikonfirmasi.\n\nSilakan tunjukkan pesan ini atau E-Ticket Anda saat check-in kepada pemilik kos.";

                WhatsAppService::send($tenant->no_telepon, $message);
            }
        } catch (Throwable $exception) {
            Log::error('WhatsApp pembayaran sukses gagal: '.$exception->getMessage(), [
                'payment_id' => $payment->id,
                'booking_id' => $booking->id,
            ]);
        }
    }

    protected function syncRoomParentAvailability(Kamar $kamar): void
    {
        $kamar->loadMissing('tipeKamar.kosan');

        $tipeKamar = $kamar->tipeKamar;
        $kosan = $tipeKamar?->kosan;

        if ($tipeKamar === null && $kosan === null) {
            return;
        }

        $hasAvailableRoom = Kamar::query()
            ->whereHas('tipeKamar', function ($query) use ($kosan, $tipeKamar): void {
                if ($kosan !== null) {
                    $query->where('kosan_id', $kosan->id);

                    return;
                }

                $query->whereKey($tipeKamar?->id);
            })
            ->where('status_kamar', 'tersedia')
            ->exists();

        if ($tipeKamar !== null && $this->hasColumn($tipeKamar->getTable(), 'is_available')) {
            $tipeKamar->forceFill(['is_available' => $hasAvailableRoom])->save();
        }

        if ($kosan !== null && $this->hasColumn($kosan->getTable(), 'is_available')) {
            $kosan->forceFill(['is_available' => $hasAvailableRoom])->save();
        }
    }

    protected function hasColumn(string $table, string $column): bool
    {
        static $cache = [];

        $key = $table.'.'.$column;

        if (! array_key_exists($key, $cache)) {
            $cache[$key] = Schema::hasColumn($table, $column);
        }

        return $cache[$key];
    }
}
