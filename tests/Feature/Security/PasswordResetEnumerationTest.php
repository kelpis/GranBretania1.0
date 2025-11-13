<?php

namespace Tests\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetEnumerationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Asegura que la respuesta a forgot-password no permita enumerar emails (mismos códigos de estado/redirect).
     */
    public function test_forgot_password_response_is_equal_for_existing_and_nonexisting_email()
    {
        $user = User::factory()->create();

        // petición para email existente
        $respExisting = $this->post('/forgot-password', ['email' => $user->email]);

        // petición para email inexistente
        $respNon = $this->post('/forgot-password', ['email' => 'noexiste+'.time().'@example.test']);

        $this->assertEquals($respExisting->getStatusCode(), $respNon->getStatusCode(), 'Status code differs between existing and non-existing email');

        // Comprobación adicional: si hay redirección, comprobar que la ubicación coincida (si aplica)
        if ($respExisting->isRedirection() || $respNon->isRedirection()) {
            $this->assertEquals($respExisting->headers->get('location'), $respNon->headers->get('location'));
        }
    }
}
