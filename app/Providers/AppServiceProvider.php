<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Kosan;
use App\Models\Kontrakan;
use App\Models\TipeKamar;
use App\Models\PemilikProperti;
use App\Models\User;
use App\Observers\MediaCleanupObserver;
use App\Support\MediaLibrary\PublicMirrorFileRemover;
use App\Support\MediaLibrary\StableMediaPathGenerator;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS when behind reverse proxy
        if (
            app()->environment('production') ||
            request()->server('HTTP_X_FORWARDED_PROTO') === 'https' ||
            request()->server('HTTPS') === 'on'
        ) {
            URL::forceScheme('https');
        }

        config([
            'media-library.path_generator' => StableMediaPathGenerator::class,
            'media-library.file_remover_class' => PublicMirrorFileRemover::class,
        ]);

        // Register media cleanup observers
        Kosan::observe(MediaCleanupObserver::class);
        Kontrakan::observe(MediaCleanupObserver::class);
        TipeKamar::observe(MediaCleanupObserver::class);
        PemilikProperti::observe(MediaCleanupObserver::class);
        User::observe(MediaCleanupObserver::class);

        RateLimiter::for('login', function (Request $request): Limit {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('transaction', function (Request $request): Limit {
            $userId = $request->user()?->getAuthIdentifier();

            return Limit::perMinute(10)->by($userId !== null ? 'user:'.$userId : 'ip:'.$request->ip());
        });
    }
}
