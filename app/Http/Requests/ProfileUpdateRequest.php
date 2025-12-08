<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Request para validar formularios edicion perfil
     */

    //Sanitiza datos antes de enviarlos
    protected function prepareForValidation()
    {
        $this->merge([
            'name' => trim(strip_tags($this->name)),  // Recorta espacios y elimina tags HTML
            'email' => trim($this->email),  // Recorta espacios
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
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:150',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }
}
