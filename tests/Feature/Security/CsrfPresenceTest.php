<?php

namespace Tests\Security;

use Tests\TestCase;

class CsrfPresenceTest extends TestCase
{
    /**
     * Comprueba que los formularios de login y register incluyen el token CSRF.
     */
    public function test_csrf_token_present_in_auth_forms()
    {
        $login = $this->get('/login');
        $login->assertStatus(200);
        $this->assertStringContainsString('name="_token"', $login->getContent());

        $register = $this->get('/register');
        $register->assertStatus(200);
        $this->assertStringContainsString('name="_token"', $register->getContent());
    }
}
