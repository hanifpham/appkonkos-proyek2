<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PencariKos;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Login dengan Google gagal. Silakan coba lagi.']);
        }

        // Find existing user by email
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // User exists - check if account is active
            if (! $user->status_akun) {
                return redirect()->route('login')
                    ->withErrors(['email' => 'Akun Anda telah dinonaktifkan.']);
            }

            // Mark email as verified if not yet
            if (! $user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }

            Auth::login($user, true);

        } else {
            // Create new user
            $user = User::create([
                'name'        => $googleUser->getName() ?? $googleUser->getNickname() ?? 'User Google',
                'email'       => $googleUser->getEmail(),
                'password'    => bcrypt(Str::random(32)),
                'no_telepon'  => '-',
                'role'        => 'pencari',
                'status'      => 'aktif',
                'status_akun' => true,
            ]);

            // Set email_verified_at (not in fillable, so use forceFill)
            $user->forceFill(['email_verified_at' => now()])->save();

            // Create PencariKos profile
            PencariKos::create([
                'user_id' => $user->id,
            ]);

            // Save Google profile photo if available
            if ($googleUser->getAvatar()) {
                try {
                    $user->clearMediaCollection('foto_profil');
                    $tempFile = tempnam(sys_get_temp_dir(), 'google_avatar_');
                    file_put_contents($tempFile, file_get_contents($googleUser->getAvatar()));
                    $user->addMedia($tempFile)
                        ->usingFileName('google-' . $user->id . '.jpg')
                        ->toMediaCollection('foto_profil');
                } catch (\Exception $e) {
                    // Silently fail - avatar is optional
                }
            }

            Auth::login($user, true);
        }

        // Check if phone number is missing or invalid
        if (is_null($user->no_telepon) || $user->no_telepon === '-' || empty(trim($user->no_telepon))) {
            $profileRoute = match ($user->role) {
                'superadmin' => 'superadmin.pengaturan-profil',
                'pemilik'    => 'mitra.pengaturan-profil',
                default      => 'pencari.profil',
            };

            return redirect()->route($profileRoute)
                ->with('success', 'Berhasil masuk! Mohon lengkapi Nomor WhatsApp Anda untuk kemudahan transaksi.');
        }

        // If complete, redirect to home
        return redirect()->route('home')
            ->with('success', 'Selamat datang kembali!');
    }
}
