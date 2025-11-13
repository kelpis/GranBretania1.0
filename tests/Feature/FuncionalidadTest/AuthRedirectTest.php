<?php

use function Pest\Laravel\get;

it('redirects guests from dashboard to login', function () {
    get('/dashboard')->assertRedirect(route('login')); // ajusta la ruta protegida si es otra
});