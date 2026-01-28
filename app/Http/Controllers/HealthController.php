<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HealthController extends Controller
{
    public function __invoke(Request $request)
    {
        // 1. Check DB Connection
        $dbStart = microtime(true);
        try {
            DB::connection()->getPdo();
            $dbStatus = 'ok';
        } catch (\Exception $e) {
            $dbStatus = 'error';
            Log::error('Health check DB failed: '.$e->getMessage());
        }
        $dbLatency = round((microtime(true) - $dbStart) * 1000, 2);

        // 2. Build Response Structure (Acorde a lo que pide el test)
        $response = [
            'status' => $dbStatus === 'ok' ? 'ok' : 'degraded',
            'app' => [
                'name' => config('app.name'),
                'env' => config('app.env'),
                'debug' => config('app.debug'),
                'version' => config('app.release_version', 'unknown'),
            ],
            'db' => [
                'ok' => $dbStatus === 'ok',
                'latency_ms' => $dbLatency,
                'driver' => DB::connection()->getDriverName(),
            ],
            'server_time' => now()->toIso8601String(),
        ];

        $statusCode = $dbStatus === 'ok' ? 200 : 503;

        return response()->json($response, $statusCode);
    }
}
