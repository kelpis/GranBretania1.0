<?php

use App\Utils\StringUtils;

it('comprueba función utilitaria del proyecto (ej. initials)', function () {
    // Reutilizamos initials como ejemplo de utilidad
    expect(StringUtils::initials('Pedro Almodóvar'))->toBe('PA');
    expect(StringUtils::initials('Sergio del Rio Lopez'))->toBe('SDRL');
});
