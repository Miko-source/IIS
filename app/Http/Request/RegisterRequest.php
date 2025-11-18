<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        // registrace je dostupná každému
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                  => ['required', 'string', 'max:255'],
            'surname'               => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            // password_confirmation automaticky páruje s 'confirmed'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Zadejte jméno.',
            'surname.required' => 'Zadejte příjmení.',
            'email.required' => 'Zadejte e-mail.',
            'email.email' => 'Zadejte platný e-mail.',
            'email.unique' => 'Tento e-mail je již zaregistrován.',
            'password.required' => 'Zadejte heslo.',
            'password.min' => 'Heslo musí mít alespoň 8 znaků.',
            'password.confirmed' => 'Hesla se neshodují.',
        ];
    }
}
