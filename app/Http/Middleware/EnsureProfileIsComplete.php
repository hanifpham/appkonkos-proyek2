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
            
            // Define the exact fields that must not be null/empty
            $requiredFields = ['no_telepon', 'jenis_kelamin'];

            foreach ($requiredFields as $field) {
                $value = $user->$field;

                // Strict Evaluation: incomplete ONLY IF null, "", or "-"
                if ($value === null || $value === '' || $value === '-') {
                    
                    // DEBUGGING DIAGNOSTIC TOOL
                    // TODO: Once you find the problematic column, comment out the line below:
                    dd('Middleware Error: Missing data detected in column -> ' . $field, 'Current User Data:', $user->toArray());

                    // TODO: ... and uncomment the redirect block below:
                    // return redirect()->route('mitra.pengaturan-profil')
                    //     ->with('error', 'Akses Ditolak! Anda wajib melengkapi data diri (Nomor Telepon, Jenis Kelamin, dll) sebelum dapat mengelola properti.');
                }
            }
        }

        return $next($request);
    }
}
