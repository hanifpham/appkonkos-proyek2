<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property \Illuminate\Support\Carbon|null $tgl_mulai_sewa
 * @property \Illuminate\Support\Carbon|null $tgl_selesai_sewa
 * @property int $total_biaya
 */
class Booking extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'booking';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'pencari_kos_id',
        'kamar_id',
        'kontrakan_id',
        'tgl_mulai_sewa',
        'tgl_selesai_sewa',
        'total_biaya',
        'status_booking',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tgl_mulai_sewa' => 'date',
            'tgl_selesai_sewa' => 'date',
            'total_biaya' => 'integer',
        ];
    }

    public function pencariKos(): BelongsTo
    {
        return $this->belongsTo(PencariKos::class, 'pencari_kos_id');
    }

    public function kontrakan(): BelongsTo
    {
        return $this->belongsTo(Kontrakan::class, 'kontrakan_id');
    }

    public function kamar(): BelongsTo
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'booking_id');
    }

    public function ulasan(): HasOne
    {
        return $this->hasOne(Ulasan::class, 'booking_id');
    }

    public function refund(): HasOne
    {
        return $this->hasOne(Refund::class, 'booking_id');
    }
}
