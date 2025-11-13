<?php

use function Pest\Laravel\get;

it('shows the traducciones page', function () {
    // usa route('traducciones') si existe la ruta nombrada
    get(route('traducciones'))->assertStatus(200)
        ->assertSee('Traducción profesional')
        ->assertSee('Tipos de traducción');
});