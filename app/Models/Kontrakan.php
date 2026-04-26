<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\ResolvesMediaUrls;
use App\Notifications\AdminAlert;
use App\Support\Notifications\SuperAdminNotifier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Kontrakan extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use ResolvesMediaUrls;

    protected $table = 'kontrakan';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'pemilik_properti_id',
        'nama_properti',
        'alamat_lengkap',
        'latitude',
        'longitude',
        'harga_sewa_tahun',
        'status',
        'alasan_penolakan',
        'fasilitas',
        'peraturan_kontrakan',
        'sisa_kamar',
    ];

    protected static function booted(): void
    {
        static::created(function (Kontrakan $kontrakan): void {
            if (! in_array($kontrakan->status, ['pending', 'menunggu'], true)) {
                return;
            }

            $ownerName = $kontrakan->loadMissing('pemilikProperti.user')->pemilikProperti?->user?->name ?? 'Mitra';

            SuperAdminNotifier::send(new AdminAlert(
                title: 'Properti Baru',
                message: sprintf('%s mengirim properti baru "%s" untuk ditinjau.', $ownerName, $kontrakan->nama_properti),
                icon: 'real_estate_agent',
                actionUrl: route('superadmin.moderasi.detail', ['tipe' => 'kontrakan', 'id' => $kontrakan->id]),
                actionLabel: 'Tinjau Properti',
                color: 'blue',
            ));
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'harga_sewa_tahun' => 'integer',
            'sisa_kamar' => 'integer',
        ];
    }

    public function pemilikProperti(): BelongsTo
    {
        return $this->belongsTo(PemilikProperti::class, 'pemilik_properti_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'kontrakan_id');
    }

    public function ulasan(): HasMany
    {
        return $this->hasMany(Ulasan::class, 'kontrakan_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('foto_properti')->singleFile();
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
