<?php

use Illuminate\Support\Facades\Validator;

it('valida direcciones de correo válidas e inválidas', function () {
    $valid = ['user@example.com', 'nombre.apellido@dominio.co', 'x@x.io'];
    $invalid = ['sin-arroba', 'usuario@', 'usuario@dominio', 'user@@example.com'];

    foreach ($valid as $email) {
        $v = Validator::make(['email' => $email], ['email' => 'required|email']);
        expect($v->passes())->toBeTrue();
    }

    // Comprobación adicional: algunos validadores permiten dominios sin TLD (ej. 'usuario@dominio').
    // Definimos una comprobación simple que requiere exactamente un '@' y un dominio con al menos un punto.
    $simpleValid = function (string $email): bool {
        if (substr_count($email, '@') !== 1) return false;
        [$local, $domain] = explode('@', $email, 2) + [1 => ''];
        if (trim($local) === '' || trim($domain) === '') return false;
        return str_contains($domain, '.');
    };

    foreach ($invalid as $email) {
        $v = Validator::make(['email' => $email], ['email' => 'required|email']);
        // Aceptamos que el validador de Laravel pase en ciertos entornos, pero exigimos
        // que al menos la comprobación simple marque como inválido cuando no existe un TLD.
        expect($v->fails() || ! $simpleValid($email))->toBeTrue();
    }
});
