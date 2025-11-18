<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        // login formulár je dostupný každému
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Zadejte e-mail.',
            'email.email' => 'Zadejte platný e-mail.',
            'password.required' => 'Zadejte heslo.',
        ];
    }
}
