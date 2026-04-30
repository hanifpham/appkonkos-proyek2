<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class RoleLoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        if ($request->hasSession()) {
            $request->session()->forget('login_portal');
        }

        return redirect()->to($this->redirectPath((string) $request->user()->role));
    }

    protected function redirectPath(string $role): string
    {
        return match ($role) {
            'superadmin' => route('superadmin.dashboard'),
            'pemilik' => route('mitra.dashboard'),
            default => '/',
        };
    }
}
