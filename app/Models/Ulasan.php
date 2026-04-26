<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ulasan extends Model
{
    use HasFactory;

    protected $table = 'ulasan';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'booking_id',
        'pencari_kos_id',
        'kosan_id',
        'kontrakan_id',
        'rating',
        'komentar',
        'balasan_pemilik',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function pencariKos(): BelongsTo
    {
        return $this->belongsTo(PencariKos::class, 'pencari_kos_id');
    }

    public function kosan(): BelongsTo
    {
        return $this->belongsTo(Kosan::class, 'kosan_id');
    }

    public function kontrakan(): BelongsTo
    {
        return $this->belongsTo(Kontrakan::class, 'kontrakan_id');
    }
}
