<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route; // Necesario para logs si lo usas aquí

// Tu ruta original
Route::get('/', fn () => view('dashboard'));



// --- REQUISITO DEL TP: Simulación de Log con User ID ---
// Esto cumple el punto "Logs con user_id" y "release context"
Route::get('/simular-venta', function () {
    Log::info('Nueva venta registrada', ['user_id' => rand(1, 100), 'monto' => rand(100, 500)]);

    return 'Venta simulada y logueada. Revisa los logs.';
});
