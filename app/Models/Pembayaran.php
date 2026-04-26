<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pembayaran extends Model
{
    use HasFactory;

    public const SUCCESS_STATUSES = ['lunas', 'settlement', 'capture'];

    public const PENDING_STATUSES = ['pending', 'authorize', 'challenge'];

    public const FAILED_STATUSES = ['gagal', 'deny', 'cancel', 'expire', 'failure'];

    public const REFUND_STATUSES = ['refund', 'partial_refund', 'chargeback', 'partial_chargeback'];

    protected $table = 'pembayaran';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'booking_id',
        'metode_bayar',
        'waktu_bayar',
        'nominal_bayar',
        'status_bayar',
        'url_struk_pdf',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'status_midtrans',
        'snap_token',
        'snap_redirect_url',
        'fraud_status',
        'payload_midtrans',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'waktu_bayar' => 'datetime',
            'nominal_bayar' => 'integer',
            'payload_midtrans' => 'array',
        ];
    }

    /**
     * @return list<string>
     */
    public static function successStatuses(): array
    {
        return self::SUCCESS_STATUSES;
    }

    /**
     * @return list<string>
     */
    public static function pendingStatuses(): array
    {
        return self::PENDING_STATUSES;
    }

    /**
     * @return list<string>
     */
    public static function failedStatuses(): array
    {
        return self::FAILED_STATUSES;
    }

    public function normalizedStatus(): string
    {
        $status = strtolower(trim((string) $this->status_bayar));

        if (in_array($status, self::SUCCESS_STATUSES, true)) {
            return 'lunas';
        }

        if (in_array($status, self::FAILED_STATUSES, true)) {
            return 'gagal';
        }

        if (in_array($status, self::REFUND_STATUSES, true)) {
            return 'refund';
        }

        if (in_array($status, self::PENDING_STATUSES, true)) {
            return 'pending';
        }

        return $status !== '' ? $status : 'pending';
    }

    public function isSuccessful(): bool
    {
        return $this->normalizedStatus() === 'lunas';
    }

    public function isPending(): bool
    {
        return $this->normalizedStatus() === 'pending';
    }

    public function isFailed(): bool
    {
        return $this->normalizedStatus() === 'gagal';
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function refund(): HasOne
    {
        return $this->hasOne(Refund::class, 'pembayaran_id');
    }
}
