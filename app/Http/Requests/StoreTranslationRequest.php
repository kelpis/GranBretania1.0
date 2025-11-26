<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Recaptcha;
use Illuminate\Support\Facades\Auth;

class StoreTranslationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Solo permitir peticiones con el usuario autentificado
        // Ruta protegida.
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'source_lang' => 'required|string|max:10',
            'target_lang' => 'required|string|max:10|different:source_lang',
            'urgency' => 'nullable|in:normal,alta',
            'file' => 'required|file|mimes:pdf,doc,docx,odt,txt,rtf|max:10240', // 10MB
            'comments' => 'nullable|string|max:2000',
            'gdpr' => 'accepted',
            //Validar reCAPTCHA v3 con un umbral conservador (0.5) y con la acción esperada 'translation'
            'g-recaptcha-response' => ['required', new Recaptcha(0.5, 'translation')],
        ];
    }

    public function messages(): array
    {
        return [
            'file.mimes' => 'Formato de archivo no soportado. Tipos permitidos: PDF, DOC, DOCX, ODT, TXT, RTF.',
            'file.max' => 'El archivo es demasiado grande. Tamaño máximo permitido: 10MB.',
            'g-recaptcha-response.required' => 'Por favor completa el reCAPTCHA antes de enviar el formulario.',
        ];
    }
}
