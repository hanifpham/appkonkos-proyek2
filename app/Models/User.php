<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected static function booted(): void
    {
        static::deleting(function (User $user): void {
            if ($user->profile_photo_path) {
                Storage::disk($user->profilePhotoDisk())->delete($user->profile_photo_path);
            }
        });

        static::updating(function (User $user): void {
            if ($user->isDirty('profile_photo_path')) {
                $oldPath = $user->getOriginal('profile_photo_path');
                if ($oldPath) {
                    Storage::disk($user->profilePhotoDisk())->delete($oldPath);
                }
            }
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
        'profile_photo_path',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'status_akun' => 'boolean',
            'password' => 'hashed',
        ];
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
}