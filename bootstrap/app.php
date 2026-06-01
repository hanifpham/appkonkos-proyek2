<?php

use App\Http\Middleware\RedirectUnverifiedUsers;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(
            at: '*',
            headers: \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_PREFIX
        );
        $middleware->validateCsrfTokens(except: [
            'api/midtrans/notifications',
            'api/midtrans/callback',
            'midtrans/callback',
        ]);

        $middleware->alias([
            'redirect.unverified' => RedirectUnverifiedUsers::class,
            'role' => RoleMiddleware::class,
        ]);

        $middleware->redirectUsersTo(function (Request $request) {
            $role = $request->user()?->role ?? 'pencari';

            return match ($role) {
                'superadmin' => route('superadmin.dashboard', absolute: false),
                'pemilik' => route('mitra.dashboard', absolute: false),
                default => route('dashboard', absolute: false),
            };
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
