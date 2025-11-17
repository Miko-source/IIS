<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name'     => ['required','string','max:255'],
            'surname' => ['required', 'string', 'max:255'],
            
            'email'    => ['required','email','unique:users,email'],
            'password' => ['required','min:6','confirmed']
        ];
    }

    public function authorize()
    {
        return true;
    }
}
