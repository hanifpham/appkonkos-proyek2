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
        // Force HTTPS for signed URL verification
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
            \Illuminate\Support\Facades\URL::forceRootUrl('https://appkonkos.my.id');
        }

        $middleware->trustProxies(
            at: '*',
            headers: \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_FOR |
                \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_HOST |
                \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_PORT |
                \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_PROTO |
                \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_PREFIX
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
