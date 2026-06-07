<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Notifications\VerifyEmailMobile;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasApiTokens;
    use HasFactory;
    use InteractsWithMedia;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected static function booted(): void
    {
        static::deleting(function (User $user): void {
            $user->clearMediaCollection('foto_profil');
        });
    }

    protected $fillable = [
        'name',
        'email',
        'no_telepon',
        'jenis_kelamin',
        'role',
        'status',
        'status_akun',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'status_akun' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('foto_profil')
            ->singleFile()
            ->useDisk('public');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(200)
            ->nonQueued();
    }

    public function pencariKos(): HasOne
    {
        return $this->hasOne(PencariKos::class, 'user_id');
    }

    public function pemilikProperti(): HasOne
    {
        return $this->hasOne(PemilikProperti::class, 'user_id');
    }

    public function favorits(): HasMany
    {
        return $this->hasMany(Favorit::class, 'user_id');
    }
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailMobile);
    }
}