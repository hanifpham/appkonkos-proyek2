<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\ResolvesMediaUrls;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TipeKamar extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use ResolvesMediaUrls;

    protected $table = 'tipe_kamar';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'kosan_id',
        'nama_tipe',
        'harga_per_bulan',
        'fasilitas_tipe',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'harga_per_bulan' => 'integer',
        ];
    }

    public function kosan(): BelongsTo
    {
        return $this->belongsTo(Kosan::class, 'kosan_id');
    }

    public function kamar(): HasMany
    {
        return $this->hasMany(Kamar::class, 'tipe_kamar_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('foto_interior')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if (! function_exists('imagecreatefromstring') && ! class_exists('Imagick')) {
            return;
        }

        $this->addMediaConversion('webp')
            ->format('webp')
            ->nonQueued();
    }
}
