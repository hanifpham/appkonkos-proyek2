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
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Kosan extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use ResolvesMediaUrls;

    protected $table = 'kosan';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'pemilik_properti_id',
        'nama_properti',
        'alamat_lengkap',
        'latitude',
        'longitude',
        'jenis_kos',
        'status',
        'alasan_penolakan',
        'peraturan_kos',
    ];

    protected static function booted(): void
    {
        static::created(function (Kosan $kosan): void {
            if (! in_array($kosan->status, ['pending', 'menunggu'], true)) {
                return;
            }

            $ownerName = $kosan->loadMissing('pemilikProperti.user')->pemilikProperti?->user?->name ?? 'Mitra';

            SuperAdminNotifier::send(new AdminAlert(
                title: 'Properti Baru',
                message: sprintf('%s mengirim properti baru "%s" untuk ditinjau.', $ownerName, $kosan->nama_properti),
                icon: 'real_estate_agent',
                actionUrl: route('superadmin.moderasi.detail', ['tipe' => 'kosan', 'id' => $kosan->id]),
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
        ];
    }

    public function pemilikProperti(): BelongsTo
    {
        return $this->belongsTo(PemilikProperti::class, 'pemilik_properti_id');
    }

    public function ulasan(): HasMany
    {
        return $this->hasMany(Ulasan::class, 'kosan_id');
    }

    public function tipeKamar(): HasMany
    {
        return $this->hasMany(TipeKamar::class, 'kosan_id');
    }

    public function getHargaRangeAttribute(): ?string
    {
        /** @var Collection<int, TipeKamar> $tipeKamar */
        $tipeKamar = $this->relationLoaded('tipeKamar')
            ? $this->getRelation('tipeKamar')
            : $this->tipeKamar()->get();

        $hargaTermurah = $tipeKamar->min('harga_per_bulan');
        $hargaTermahal = $tipeKamar->max('harga_per_bulan');

        if ($hargaTermurah === null || $hargaTermahal === null) {
            return null;
        }

        if ((int) $hargaTermurah === (int) $hargaTermahal) {
            return 'Rp '.number_format((int) $hargaTermurah, 0, ',', '.').' / bulan';
        }

        return 'Rp '.number_format((int) $hargaTermurah, 0, ',', '.')
            .' - Rp '.number_format((int) $hargaTermahal, 0, ',', '.')
            .' / bulan';
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
