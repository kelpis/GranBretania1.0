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
        // Only allow the request if the user is authenticated.
        // The routes are also protected with the 'auth' middleware so guests will be
        // redirected to login; this is an additional safety check.
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
            'name' => 'required|string|max:120',
            'email'=> 'required|email',
            'source_lang' => 'required|string|max:10',
            'target_lang' => 'required|string|max:10|different:source_lang',
            'urgency' => 'nullable|in:normal,alta',
            // Allowed file types: PDF, Word (doc, docx), OpenDocument (odt), TXT, RTF
            // Increase allowed size to 10MB (10240 KB)
            'file' => 'required|file|mimes:pdf,doc,docx,odt,txt,rtf|max:10240', // 10MB
            'comments' => 'nullable|string|max:2000',
            'gdpr' => 'accepted',
            // validate reCAPTCHA v3 with a conservative threshold (0.5) and expected action 'translation'
            'g-recaptcha-response' => ['required', new Recaptcha(0.5, 'translation')],
        ];
    }

    public function messages(): array
    {
        return [
            'file.mimes' => 'Formato de archivo no soportado. Tipos permitidos: PDF, DOC, DOCX, ODT, TXT, RTF.',
            'file.max' => 'El archivo es demasiado grande. Tamaño máximo permitido: 10MB.',
        ];
    }

}
