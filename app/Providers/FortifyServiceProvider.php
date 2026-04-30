<?php

declare(strict_types=1);

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Responses\RoleLoginResponse;
use App\Http\Responses\RoleTwoFactorLoginResponse;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Laravel\Fortify\Fortify;
use App\Models\User;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponseContract::class, RoleLoginResponse::class);
        $this->app->singleton(TwoFactorLoginResponseContract::class, RoleTwoFactorLoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                // Ambil tipe portal dari session (diset di routes/web.php)
                $loginPortal = session('login_portal', 'pencari');
                
                if ($loginPortal === 'pencari') {
                    if ($user->role !== 'pencari') {
                        throw ValidationException::withMessages([
                            'email' => 'Akun ini terdaftar sebagai Admin/Mitra. Silakan login melalui portal Admin Kos & Kontrakan.',
                        ]);
                    }
                } elseif ($loginPortal === 'pemilik') {
                    // Portal mitra/pemilik membolehkan role pemilik dan superadmin
                    if (!in_array($user->role, ['pemilik', 'superadmin'])) {
                        throw ValidationException::withMessages([
                            'email' => 'Akun ini terdaftar sebagai Pencari Kos. Silakan login melalui portal Pencari Kos & Kontrakan.',
                        ]);
                    }
                } elseif ($loginPortal === 'superadmin') {
                    if ($user->role !== 'superadmin') {
                        throw ValidationException::withMessages([
                            'email' => 'Akses ditolak. Anda bukan Super Admin.',
                        ]);
                    }
                }

                return $user;
            }
            
            return false;
        });
    }
}
