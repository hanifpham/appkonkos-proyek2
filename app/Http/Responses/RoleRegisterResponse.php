<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RoleRegisterResponse implements RegisterResponseContract
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
            return new JsonResponse('', 201);
        }

        $user = $request->user();

        if (! $user) {
            return redirect()->to('/');
        }

        return redirect()->to($this->redirectPath($user->role));
    }

    protected function redirectPath(string $role): string
    {
        return match ($role) {
            'superadmin' => '/superadmin/dashboard',
            'pemilik'    => '/mitra/dashboard',
            'pencari'    => '/',
            default      => '/',
        };
    }
}
