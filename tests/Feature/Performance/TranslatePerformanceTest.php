<?php

namespace Tests\Performance;

use Tests\TestCase;

class TranslatePerformanceTest extends TestCase
{
    /**
     * Mide el tiempo de respuesta promedio de la página de traducciones en ms.
     */
    public function test_translate_page_average_response_time()
    {
        $iterations = 4;
        $times = [];

        // Warmup
        $this->get('/traducciones');

        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            $response = $this->get('/traducciones');
            $duration = (microtime(true) - $start) * 1000; // ms
            $response->assertStatus(200);
            $times[] = $duration;
        }

        $avg = array_sum($times) / count($times);
        $thresholdMs = 600; // página más pesada

        $this->assertLessThanOrEqual($thresholdMs, $avg, sprintf('Average response time %.2fms exceeds %dms', $avg, $thresholdMs));
    }
}
