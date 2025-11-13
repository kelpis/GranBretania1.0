<?php

namespace Tests\Performance;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class QueriesPerformanceTest extends TestCase
{
    /**
     * Cuenta el número de queries ejecutados al renderizar /clases y verifica que no sea excesivo.
     */
    public function test_clases_query_count_is_reasonable()
    {
        DB::flushQueryLog();
        DB::enableQueryLog();

        $response = $this->get('/clases');
        $response->assertStatus(200);

        $queries = DB::getQueryLog();
        $count = count($queries);

        // Umbral razonable (ajustable según la complejidad de la página)
        $maxQueries = 20;

        $this->assertLessThanOrEqual($maxQueries, $count, sprintf('Query count %d exceeds %d', $count, $maxQueries));
    }
}
