<?php

use function Pest\Laravel\get;

it('shows the homepage', function () {
    get('/')->assertStatus(200)
        ->assertSee('Gran Bretania'); // ajusta el texto exacto si hace falta
});