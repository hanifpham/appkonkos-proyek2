<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PencariKos extends Model
{
    use HasFactory;

    protected $table = 'pencari_kos';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'jenis_kelamin',
        'tanggal_lahir',
        'pekerjaan',
        'nama_instansi',
        'kota_asal',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'pencari_kos_id');
    }

    public function ulasan(): HasMany
    {
        return $this->hasMany(Ulasan::class, 'pencari_kos_id');
    }
}
