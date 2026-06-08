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

        if ($user && $user->role === 'pemilik') {
            $isProfileIncomplete = false;

            // 1. Cek field di tabel users
            $userFields = ['no_telepon'];
            foreach ($userFields as $field) {
                $value = $user->$field;
                if ($value === null || $value === '' || $value === '-') {
                    $isProfileIncomplete = true;
                    break;
                }
            }

            // 2. Cek field di tabel pemilik_properti
            if (! $isProfileIncomplete) {
                $pemilik = $user->pemilikProperti;
                
                if (! $pemilik) {
                    $isProfileIncomplete = true;
                } else {
                    $pemilikFields = ['alamat_domisili', 'nama_bank', 'nomor_rekening'];
                    foreach ($pemilikFields as $field) {
                        $value = $pemilik->$field;
                        if ($value === null || $value === '' || $value === '-') {
                            $isProfileIncomplete = true;
                            break;
                        }
                    }
                }
            }

            if ($isProfileIncomplete) {
                return redirect()->route('mitra.pengaturan-profil')
                    ->with('error', 'Akses Ditolak! Anda wajib melengkapi Nomor Telepon, Alamat Domisili, dan Data Rekening Bank sebelum dapat mengelola properti.');
            }
        }

        return $next($request);
    }
}
