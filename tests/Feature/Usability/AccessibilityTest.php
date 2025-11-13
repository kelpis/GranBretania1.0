<?php

namespace Tests\Feature\Usability;

use Tests\TestCase;

class AccessibilityTest extends TestCase
{
    /**
     * Comprueba que algunas imágenes de la home contienen atributo alt.
     */
    public function test_images_have_alt_attributes_on_homepage()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $content = $response->getContent();

        // Buscamos al menos una etiqueta img con atributo alt no vacío
        $this->assertMatchesRegularExpression('/<img[^>]*alt=["\']{1}[^"\']+?["\']{1}/s', $content, 'No se encontró ninguna imagen con atributo alt en la home');
    }
}
