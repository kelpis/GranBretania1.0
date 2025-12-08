<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Recaptcha;

//Request para validarsolicitudes de formulario de contacto.


class StoreContactRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     * En este caso, permite a cualquier usuario (autenticado o no) usar el formulario.
     */
    public function authorize(): bool
    {
        return true; // Permite a cualquier usuario usar el formulario.
    }

    
    // Sanitiza campos eliminando tags HTML y recortando espacios.
     
    protected function prepareForValidation()
    {
        $this->merge([
            'name' => trim(strip_tags($this->name)),  // Recorta espacios y elimina tags HTML.
            'email' => trim($this->email),  // Recorta espacios.
            'subject' => trim(strip_tags($this->subject)),  // Recorta espacios y elimina tags HTML.
            'message' => trim(strip_tags($this->message)),  // Recorta espacios y elimina tags HTML.
        ]);
    }

    /**
     * Obtiene las reglas de validación que aplican a la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'    => 'required|string|max:100', // Nombre obligatorio, string, máximo 100 caracteres.
            'email'   => 'required|email|max:150', // Email obligatorio, válido, máximo 150 caracteres.
            'subject' => 'nullable|string|max:160', // Asunto opcional, string, máximo 160 caracteres.
            'message' => 'required|string|max:2000', // Mensaje obligatorio, string, máximo 2000 caracteres.
            'gdpr'    => 'accepted', // Aceptación de GDPR obligatoria.
            // Validar reCAPTCHA v3 con umbral 0.5 y acción 'contact'.
            'g-recaptcha-response' => ['required', new Recaptcha(0.5, 'contact')],
        ];
    }

    // Mensajes personalizados para errores de validación.
    public function messages()
    {
        return [
            'name.required' => 'Por favor indica tu nombre.',
            'email.required' => 'Por favor indica un correo válido.',
            'message.required' => 'Escribe tu mensaje antes de enviarlo.',
            'gdpr.accepted' => 'Debes aceptar la política de protección de datos para continuar.',
            'g-recaptcha-response.required' => 'Por favor completa el reCAPTCHA antes de enviar el formulario.',
        ];
    }
}
