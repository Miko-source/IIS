<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        // Create User
        $user = User::create([
            'name'     => $data['name'],
            'surname'  => $data['surname'], 
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'worker'
        ]);

        // Automat sign in
        Auth::login($user);

        // dashboard
        return redirect('/dashboard')->with('success', 'Registrace proběhla úspěšně.');
    }
}
