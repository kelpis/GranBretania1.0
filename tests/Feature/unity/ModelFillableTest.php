<?php

use App\Models\User;

it('verifica que el modelo User tiene fillables definidos correctamente', function () {
    $user = new User();
    $fillable = $user->getFillable();

    // Campos esperados básicos
    $expected = ['name', 'email', 'password'];

    foreach ($expected as $field) {
        expect(in_array($field, $fillable))->toBeTrue();
    }
});
