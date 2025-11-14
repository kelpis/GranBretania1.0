<?php

use Illuminate\Support\Facades\Hash;

it('hashea contraseñas y verifica mediante Hash::check', function () {
    $plain = 'SeCr3tP4ss!';
    $hash = Hash::make($plain);

    // El hash no debe ser igual al texto plano
    expect($hash)->not->toBe($plain);

    // Hash::check debe validar correctamente
    expect(Hash::check($plain, $hash))->toBeTrue();
});
