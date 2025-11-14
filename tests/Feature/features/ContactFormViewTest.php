<?php

use function Pest\Laravel\get;

it('realiza una petición GET al formulario de contacto (controller + view)', function () {
    $response = get(route('contact.create'));
    expect($response->getStatusCode())->toBe(200);

    $html = (string) $response->getContent();
    // Comprobar campos básicos del formulario
    expect(str_contains($html, 'name="name"'))->toBeTrue();
    expect(str_contains($html, 'name="email"'))->toBeTrue();
    expect(str_contains($html, 'name="message"'))->toBeTrue();
});
