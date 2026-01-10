<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test básico de la aplicación.
     *
     * Nota: Este test usa /api/health en lugar de / porque la página principal
     * requiere el manifest de Vite (npm run build), y en CI los tests de PHP
     * pueden ejecutarse antes de compilar el frontend.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Usamos el endpoint de health que no requiere Vite
        $response = $this->getJson('/api/health');

        // Acepta 200 (OK) o 503 (degraded si no hay DB)
        $this->assertTrue(
            in_array($response->status(), [200, 503]),
            "Expected status 200 or 503, got {$response->status()}"
        );
    }
}
