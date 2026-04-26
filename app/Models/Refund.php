<?php

declare(strict_types=1);

namespace App\Models;

use App\Notifications\AdminAlert;
use App\Support\Notifications\SuperAdminNotifier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Refund extends Model
{
    use HasFactory;

    protected $table = 'refund';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'booking_id',
        'pembayaran_id',
        'nominal_refund',
        'alasan_refund',
        'status_refund',
        'bukti_transfer_refund',
    ];

    protected static function booted(): void
    {
        static::created(function (Refund $refund): void {
            if ($refund->status_refund !== 'pending') {
                return;
            }

            $refund->loadMissing('booking.pencariKos.user');

            $requesterName = $refund->booking?->pencariKos?->user?->name ?? 'Pengguna';

            SuperAdminNotifier::send(new AdminAlert(
                title: 'Pengajuan Refund Baru',
                message: sprintf('%s mengajukan refund baru sebesar Rp %s.', $requesterName, number_format((int) $refund->nominal_refund, 0, ',', '.')),
                icon: 'assignment_return',
                actionUrl: route('superadmin.refund'),
                actionLabel: 'Buka Refund',
                reason: $refund->alasan_refund,
                color: 'amber',
            ));
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'nominal_refund' => 'integer',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function pembayaran(): BelongsTo
    {
        return $this->belongsTo(Pembayaran::class, 'pembayaran_id');
    }
}
