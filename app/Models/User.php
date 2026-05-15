<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected static function booted(): void
    {
        static::deleting(function (User $user): void {
            // Delete profile photo on user deletion
            if ($user->profile_photo_path) {
                Storage::disk($user->profilePhotoDisk())->delete($user->profile_photo_path);
            }
        });

        static::updating(function (User $user): void {
            // Delete old profile photo if updated
            if ($user->isDirty('profile_photo_path')) {
                $oldPath = $user->getOriginal('profile_photo_path');
                if ($oldPath) {
                    Storage::disk($user->profilePhotoDisk())->delete($oldPath);
                }
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
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
