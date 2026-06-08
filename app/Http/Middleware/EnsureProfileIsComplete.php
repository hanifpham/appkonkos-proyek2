<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsComplete
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // 1. Cek apakah user sudah login dan mempunyai role 'pemilik' (Mitra)
        if ($user && $user->role === 'pemilik') {
            
            // 2. Cek apakah field esensial masih kosong atau default
            $isProfileIncomplete = 
                is_null($user->no_telepon) || 
                empty(trim($user->no_telepon)) || 
                $user->no_telepon === '-' || 
                is_null($user->jenis_kelamin);

            // 3. Jika profil belum lengkap, blokir dan arahkan ke pengaturan profil
            if ($isProfileIncomplete) {
                return redirect()->route('mitra.pengaturan-profil')
                    ->with('error', 'Akses Ditolak! Anda wajib melengkapi data diri (Nomor Telepon, Jenis Kelamin, dll) sebelum dapat mengelola properti.');
            }
        }

        return $next($request);
    }
}
