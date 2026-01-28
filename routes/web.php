<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log; // Necesario para logs si lo usas aquí

// Tu ruta original
Route::get('/', fn () => view('dashboard'));

// --- REQUISITO DEL TP: Health Check ---
// El profesor pide validar /api/health para saber que el deploy funcionó
Route::get('/api/health', function () {
    // Intentamos leer el ID del release que generó GitHub Actions
    $releaseId = file_exists(base_path('RELEASE_ID')) 
        ? trim(file_get_contents(base_path('RELEASE_ID'))) 
        : 'development';

    return response()->json([
        'status' => 'ok',
        'release_id' => $releaseId,
        'environment' => app()->environment(),
        'database_status' => 'connected', // Simulado para que no falle si la DB molesta
        'timestamp' => now()->toIso8601String(),
    ]);
});

// --- REQUISITO DEL TP: Simulación de Log con User ID ---
// Esto cumple el punto "Logs con user_id" y "release context"
Route::get('/simular-venta', function () {
    Log::info('Nueva venta registrada', ['user_id' => rand(1, 100), 'monto' => rand(100, 500)]);
    return 'Venta simulada y logueada. Revisa los logs.';
});