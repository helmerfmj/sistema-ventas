<?php

namespace App\Providers;

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
    public function boot()
    {
        // Fix común para migraciones en Laravel viejos/MariaDB
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);

        // TAREA CD: Observabilidad y Contexto en Logs
        try {
            $release = file_exists(base_path('RELEASE_ID')) ? trim(file_get_contents(base_path('RELEASE_ID'))) : 'unknown';
            
            \Illuminate\Support\Facades\Log::withContext([
                'release' => $release,
                'env' => app()->environment(),
                // 'user_id' se inyectará cuando haya auth, pero esto cumple el requisito base
            ]);
        } catch (\Throwable $e) {
            // Fallo silencioso si el log no está listo
        }
    }
}
