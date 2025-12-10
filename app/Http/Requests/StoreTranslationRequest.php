<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Recaptcha;
use Illuminate\Support\Facades\Auth;

//Request para validar solicitudes de traducción.


class StoreTranslationRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     * Solo permite peticiones con el usuario autenticado (ruta protegida).
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    // Sanitiza datos :limpia campos de tags HTML y espacios.
    protected function prepareForValidation()
    {
        $this->merge([
            'email' => $this->email ? trim($this->email) : null,
            'source_lang' => trim(strip_tags($this->source_lang)), // Limpia idioma origen.
            'target_lang' => trim(strip_tags($this->target_lang)), // Limpia idioma destino.
            'urgency' => $this->urgency ? trim(strip_tags($this->urgency)) : null, // Limpia urgencia si existe.
            'comments' => $this->comments ? trim(strip_tags($this->comments)) : null, // Limpia comentarios si existen.
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
            // Email opcional si el usuario no está enlazado (mantener por coherencia aunque authorize requiere auth)
            'email' => ['nullable', 'regex:/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/', 'max:150'],
            'source_lang' => 'required|string|max:10', // Idioma origen obligatorio, string, máximo 10 caracteres.
            'target_lang' => 'required|string|max:10|different:source_lang', // Idioma destino obligatorio, diferente al origen.
            'urgency' => 'nullable|in:normal,alta', // Urgencia opcional, valores permitidos: normal o alta.
            'file' => 'required|file|mimes:pdf,doc,docx,odt,txt,rtf|max:10240', // Archivo obligatorio, tipos permitidos, máximo 10MB.
            'comments' => 'nullable|string|max:2000', // Comentarios opcionales, máximo 2000 caracteres.
            'gdpr' => 'accepted', // Aceptación de GDPR obligatoria.
            // Validar reCAPTCHA v3 con umbral 0.5 y acción 'translation'.
            'g-recaptcha-response' => ['required', new Recaptcha(0.5, 'translation')],
        ];
    }

    // Mensajes personalizados para errores de validación.
    public function messages(): array
    {
        return [
            'email.regex' => 'El formato del correo no es válido.',
            'file.mimes' => 'Formato de archivo no soportado. Tipos permitidos: PDF, DOC, DOCX, ODT, TXT, RTF.',
            'file.max' => 'El archivo es demasiado grande. Tamaño máximo permitido: 10MB.',
            'g-recaptcha-response.required' => 'Por favor completa el reCAPTCHA antes de enviar el formulario.',
        ];
    }
}
