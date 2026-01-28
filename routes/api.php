<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\CompraController;
use App\Http\Controllers\Api\PermisoController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\ProveedorController;
use App\Http\Controllers\Api\ReporteController;
use App\Http\Controllers\Api\RolController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\VentaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- TAREA CD: HEALTH CHECK 
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'server_time' => now(),
        'env' => app()->environment(),
        'release' => config('app.release_version', 'unknown'), // Leera lo que pongamos en AppServiceProvider
    ], 200);
});


Route::prefix('v1')->group(function () {

    // Auth Público
    Route::post('auth/login', [AuthController::class, 'login']);

    // Rutas Protegidas
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::prefix('auth')->controller(AuthController::class)->group(function () {
            Route::post('logout', 'logout');
            Route::get('user', 'user');
        });

        // Categorías
        Route::controller(CategoriaController::class)->prefix('categorias')->group(function () {
            Route::patch('{id}/toggle-estado', 'toggleEstado');
        });
        Route::apiResource('categorias', CategoriaController::class);

        // Productos
        Route::controller(ProductoController::class)->prefix('productos')->group(function () {
            Route::get('generar-codigo', 'generarCodigo');
            Route::patch('{id}/toggle-estado', 'toggleEstado');
            Route::post('{id}', 'update');
        });
        Route::apiResource('productos', ProductoController::class);

        // Clientes
        Route::controller(ClienteController::class)->prefix('clientes')->group(function () {
            Route::get('generar-codigo', 'generarCodigo');
            Route::patch('{id}/toggle-estado', 'toggleEstado');
        });
        Route::apiResource('clientes', ClienteController::class);

        // Proveedores
        Route::controller(ProveedorController::class)->prefix('proveedores')->group(function () {
            Route::get('generar-codigo', 'generarCodigo');
            Route::patch('{id}/toggle-estado', 'toggleEstado');
        });
        Route::apiResource('proveedores', ProveedorController::class);

        // Compras
        Route::controller(CompraController::class)->prefix('compras')->group(function () {
            Route::get('generar-codigo', 'generarCodigo');
            Route::get('proveedores', 'getProveedores');
            Route::get('productos', 'getProductos');
            Route::patch('{id}/completar', 'completar');
            Route::patch('{id}/anular', 'anular');
        });
        Route::apiResource('compras', CompraController::class);

        // Ventas
        Route::controller(VentaController::class)->prefix('ventas')->group(function () {
            Route::get('generar-codigo', 'generarCodigo');
            Route::get('clientes', 'getClientes');
            Route::get('productos', 'getProductos');
            Route::patch('{id}/completar', 'completar');
            Route::patch('{id}/anular', 'anular');
        });
        Route::apiResource('ventas', VentaController::class);

        // Roles
        Route::controller(RolController::class)->prefix('roles')->group(function () {
            Route::get('generar-codigo', 'generarCodigo');
            Route::get('permisos', 'getPermisos');
            Route::patch('{id}/permisos', 'asignarPermisos');
            Route::patch('{id}/toggle-estado', 'toggleEstado');
        });
        Route::apiResource('roles', RolController::class);

        // Permisos
        Route::controller(PermisoController::class)->prefix('permisos')->group(function () {
            Route::get('modulos', 'getModulos');
            Route::get('agrupados', 'getAgrupados');
        });
        Route::apiResource('permisos', PermisoController::class);

        // Usuarios
        Route::controller(UsuarioController::class)->prefix('usuarios')->group(function () {
            Route::get('roles', 'getRoles');
            Route::patch('{id}/roles', 'asignarRoles');
            Route::patch('{id}/toggle-estado', 'toggleEstado');
        });
        Route::apiResource('usuarios', UsuarioController::class);

        // Reportes
        Route::controller(ReporteController::class)->prefix('reportes')->group(function () {
            Route::get('dashboard', 'dashboard');
            Route::get('filtros', 'getFiltros');
            Route::get('ventas/pdf', 'ventasPdf');
            Route::get('compras/pdf', 'comprasPdf');
            Route::get('inventario/pdf', 'inventarioPdf');
            Route::get('clientes/pdf', 'clientesPdf');
            Route::get('proveedores/pdf', 'proveedoresPdf');
            Route::get('finanzas/pdf', 'finanzasPdf');
        });
    });
});
ESTO_ES_UN_ERROR_INTENCIONAL_PARA_EL_PROFESOR;