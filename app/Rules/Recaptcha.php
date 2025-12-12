<?php

namespace App\Rules;

/** @noinspection PhpDeprecationInspection */

use Illuminate\Contracts\Validation\Rule; // Deprecada 
use Illuminate\Support\Facades\Http;

// Regla de validación para reCAPTCHA (v3).
// Verifica el token enviado por el cliente contra la API de Google
class Recaptcha implements Rule
{
    // Umbral mínimo para reCAPTCHA v3 (0.0 - 1.0)
    protected float $minScore;
    // Acción esperada (opcional) para reCAPTCHA v3
    protected ?string $action;

    // Constructor: recibe el umbral mínimo y la action esperada.
    public function __construct(float $minScore = 0.5, ?string $action = null)
    {
        $this->minScore = $minScore;
        $this->action = $action;
    }

    // Comprueba el token llamando a la API de Google y validando respuesta.
    public function passes($attribute, $value)
    {
        // Recuperar secreto desde la configuración (services.php)
        $secret = config('services.recaptcha.secret');

        // Si no hay secreto configurado o no se ha enviado token, fallamos.
        if (empty($secret) || empty($value)) {
            return false;
        }

        try {
            // Enviamos la petición como form-data a la API de Google.
            // Incluimos `remoteip` para ayudar a Google a validar la petición.
            $res = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            // Si la respuesta HTTP no es 200 OK, consideramos que falló.
            if (! $res->ok()) {
                return false;
            }

            $body = $res->json();

            // La API devuelve un campo `success` que debe ser true.
            if (! (isset($body['success']) && $body['success'] === true)) {
                return false;
            }

            // Para reCAPTCHA v3 Google devuelve además un `score` (0..1).
            // Si existe, comprobamos que supere el umbral mínimo.
            if (isset($body['score']) && is_numeric($body['score'])) {
                if ($body['score'] < $this->minScore) {
                    return false;
                }
            }

            // Si se indicó una `action` esperada (v3), la respuesta debe contenerla y coincidir.
            if ($this->action !== null && isset($body['action'])) {
                if ($body['action'] !== $this->action) {
                    return false;
                }
            }

            //OK: se considera válido
            return true;
        } catch (\Throwable $e) {
            // En caso de error aplicamos la política rechazamos la validación para evitar abuso.
            return false;
        }
    }

    // Mensaje mostrado al usuario cuando la verificación falla.
    public function message()
    {
        return 'No se pudo verificar el reCAPTCHA. Por favor inténtalo de nuevo.';
    }
}
