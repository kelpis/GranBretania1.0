<?php

namespace Tests\Feature\Usability;

use Tests\TestCase;

class NavigationLinksTest extends TestCase
{
    /**
     * Comprueba que las rutas principales estén enlazadas desde la home.
     */
    public function test_navigation_links_present_on_homepage()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $content = $response->getContent();

        // Verificamos que la home contiene enlaces a las rutas estáticas importantes
        $this->assertStringContainsString(route('clases'), $content);
        $this->assertStringContainsString(route('traducciones'), $content);
        $this->assertStringContainsString(route('sobremi'), $content);
        $this->assertStringContainsString(route('faq'), $content);
    }
}
