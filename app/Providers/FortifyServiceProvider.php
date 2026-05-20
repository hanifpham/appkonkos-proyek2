<?php

declare(strict_types=1);

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Responses\RoleLoginResponse;
use App\Http\Responses\RoleRegisterResponse;
use App\Http\Responses\RoleTwoFactorLoginResponse;
use App\Http\Responses\RoleVerifyEmailResponse;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponseContract::class, RoleLoginResponse::class);
        $this->app->singleton(RegisterResponseContract::class, RoleRegisterResponse::class);
        $this->app->singleton(TwoFactorLoginResponseContract::class, RoleTwoFactorLoginResponse::class);
        $this->app->singleton(VerifyEmailResponseContract::class, RoleVerifyEmailResponse::class);
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

        VerifyEmail::toMailUsing(function ($notifiable, string $url): MailMessage {
            $name = $notifiable->name ?: 'Pengguna APPKONKOS';
            $expiresIn = config('auth.verification.expire', 60);

            return (new MailMessage)
                ->subject('Verifikasi Email Akun APPKONKOS')
                ->greeting("Halo {$name},")
                ->line('Aktifkan akun APPKONKOS Anda dengan menekan tombol verifikasi di bawah ini.')
                ->action('Verifikasi Email Sekarang', $url)
                ->line("Link verifikasi ini berlaku selama {$expiresIn} menit.")
                ->line('Jika Anda tidak merasa membuat akun APPKONKOS, abaikan email ini.')
                ->salutation('Salam, Tim APPKONKOS');
        });

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
                    if (! in_array($user->role, ['pemilik', 'superadmin'])) {
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
