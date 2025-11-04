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
            'file' => 'required|file|max:5120', // 5MB
            'comments' => 'nullable|string|max:2000',
            'gdpr' => 'accepted',
            // validate reCAPTCHA v3 with a conservative threshold (0.5) and expected action 'translation'
            'g-recaptcha-response' => ['required', new Recaptcha(0.5, 'translation')],
        ];
    }
}
