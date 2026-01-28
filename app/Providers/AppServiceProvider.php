<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

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
        // Fix para longitud de strings en bases de datos antiguas o MariaDB
        Schema::defaultStringLength(191);

        // --- TAREA CD: OBSERVABILIDAD  
        // Intentamos leer el archivo RELEASE_ID generado por GitHub Actions
        $releaseId = 'local-dev';
        
        try {
            if (file_exists(base_path('RELEASE_ID'))) {
                $releaseId = trim(file_get_contents(base_path('RELEASE_ID')));
            }
        } catch (\Throwable $e) {
            
        }

        // Guardamos el release en config para usarlo en el Health Check
        config(['app.release_version' => $releaseId]);

        // Inyectamos contexto a TODOS los logs de la aplicación automáticamente
        Log::shareContext([
            'env' => app()->environment(),
            'release' => $releaseId,
        ]);
        
        
    }
}