<?php

namespace Tests\Feature;

use Tests\TestCase;

class HealthTest extends TestCase
{
    public function test_health_endpoint_returns_ok_or_degraded(): void
    {
        $res = $this->getJson('/api/health');

        // Accept both 200 (ok) and 503 (degraded) as valid responses
        $this->assertTrue(
            in_array($res->status(), [200, 503]),
            "Expected status 200 or 503, got {$res->status()}"
        );

        // Verify JSON structure
        $res->assertJsonStructure([
            'status',
            'timestamp',
            'app' => ['name', 'env', 'debug', 'version'],
            'db' => ['ok', 'latency_ms', 'driver'],
            'cache' => ['ok', 'driver'],
            'storage' => ['ok', 'path', 'writable'],
            'queue' => ['ok', 'driver'],
        ]);

        // Status should be 'ok' or 'degraded'
        $status = $res->json('status');
        $this->assertTrue(
            in_array($status, ['ok', 'degraded']),
            "Status should be 'ok' or 'degraded', got '{$status}'"
        );
    }

    public function test_health_endpoint_db_check_structure(): void
    {
        $res = $this->getJson('/api/health');

        $res->assertJsonStructure([
            'db' => [
                'ok',
                'latency_ms',
                'driver',
            ],
        ]);

        // Verify db.ok is boolean
        $this->assertIsBool($res->json('db.ok'));
    }

    public function test_health_endpoint_cache_check_structure(): void
    {
        $res = $this->getJson('/api/health');

        $res->assertJsonStructure([
            'cache' => [
                'ok',
                'driver',
            ],
        ]);

        $this->assertIsBool($res->json('cache.ok'));
    }

    public function test_health_endpoint_storage_check_structure(): void
    {
        $res = $this->getJson('/api/health');

        $res->assertJsonStructure([
            'storage' => [
                'ok',
                'path',
                'writable',
            ],
        ]);

        $this->assertIsBool($res->json('storage.ok'));
        $this->assertIsBool($res->json('storage.writable'));
    }
}
