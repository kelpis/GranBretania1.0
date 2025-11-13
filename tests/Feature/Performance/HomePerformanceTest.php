<?php

namespace Tests\Performance;

use Tests\TestCase;

class HomePerformanceTest extends TestCase
{
    /**
     * Mide el tiempo de respuesta promedio de la home en ms (media de varias peticiones).
     */
    public function test_homepage_average_response_time()
    {
        $iterations = 5;
        $times = [];

        // Warmup
        $this->get('/');

        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            $response = $this->get('/');
            $duration = (microtime(true) - $start) * 1000; // ms
            $response->assertStatus(200);
            $times[] = $duration;
        }

        $avg = array_sum($times) / count($times);

        // Umbral razonable para entorno de desarrollo (ajustable)
        $thresholdMs = 500;

        $this->assertLessThanOrEqual($thresholdMs, $avg, sprintf('Average response time %.2fms exceeds %dms', $avg, $thresholdMs));
    }
}
