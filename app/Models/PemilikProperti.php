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

class PemilikProperti extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use ResolvesMediaUrls;

    protected $table = 'pemilik_properti';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'alamat_domisili',
        'nik_ktp',
        'nama_bank',
        'nomor_rekening',
        'nama_pemilik_rekening',
        'status_verifikasi',
        'notif_whatsapp_pesanan_baru',
        'notif_whatsapp_pembayaran_sukses',
        'notif_whatsapp_ulasan_baru',
        'notif_email_pesanan_baru',
        'notif_email_pembayaran_sukses',
        'notif_email_ulasan_baru',
        'notif_aplikasi_pesanan_baru',
        'notif_aplikasi_pembayaran_sukses',
        'notif_aplikasi_ulasan_baru',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'notif_whatsapp_pesanan_baru' => 'boolean',
            'notif_whatsapp_pembayaran_sukses' => 'boolean',
            'notif_whatsapp_ulasan_baru' => 'boolean',
            'notif_email_pesanan_baru' => 'boolean',
            'notif_email_pembayaran_sukses' => 'boolean',
            'notif_email_ulasan_baru' => 'boolean',
            'notif_aplikasi_pesanan_baru' => 'boolean',
            'notif_aplikasi_pembayaran_sukses' => 'boolean',
            'notif_aplikasi_ulasan_baru' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kosan(): HasMany
    {
        return $this->hasMany(Kosan::class, 'pemilik_properti_id');
    }

    public function kontrakan(): HasMany
    {
        return $this->hasMany(Kontrakan::class, 'pemilik_properti_id');
    }

    public function pencairanDana(): HasMany
    {
        return $this->hasMany(PencairanDana::class, 'pemilik_properti_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('foto_ktp')->singleFile();
        $this->addMediaCollection('foto_selfie')->singleFile();
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
