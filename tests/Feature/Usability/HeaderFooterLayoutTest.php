<?php

namespace Tests\Feature\Usability;

use Tests\TestCase;

class HeaderFooterLayoutTest extends TestCase
{
    /**
     * Comprueba que existe un header/footer y que el footer contiene las clases de disposición esperadas.
     */
    public function test_footer_and_header_structure()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $content = $response->getContent();

        // Elementos básicos
        $this->assertStringContainsString('<footer', $content);
        $this->assertStringContainsString('<header', $content);

        // Clases aplicadas al footer (comprobación suave de estructura responsiva)
        $this->assertStringContainsString('md:basis-1/2', $content);
        $this->assertStringContainsString('md:basis-1/4', $content);
    }
}
