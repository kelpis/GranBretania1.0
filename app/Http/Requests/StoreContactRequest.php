<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Recaptcha;

class StoreContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // permite a cualquier usuario usar el formulario
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'name' => trim(strip_tags($this->name)),  // Recorta espacios y elimina tags HTML
            'email' => trim($this->email),  // Recorta espacios
            'subject' => trim(strip_tags($this->subject)),  // Recorta espacios y elimina tags HTML
            'message' => trim(strip_tags($this->message)),  // Recorta espacios y elimina tags HTML
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'subject' => 'nullable|string|max:160',
            'message' => 'required|string|max:2000',
            'gdpr'    => 'accepted',
            //Validar reCAPTCHA v3 con un umbral conservador (0.5) y con la acción esperada 'contact'
            'g-recaptcha-response' => ['required', new Recaptcha(0.5, 'contact')],
        ];
    }

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
