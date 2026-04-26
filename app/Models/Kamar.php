<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'tipe_kamar_id',
        'nomor_kamar',
        'status_kamar',
    ];

    public function tipeKamar(): BelongsTo
    {
        return $this->belongsTo(TipeKamar::class, 'tipe_kamar_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'kamar_id');
    }
}
