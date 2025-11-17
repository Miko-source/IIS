<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        // fail sign in
        if (!Auth::attempt($credentials)) {
            return back()
                ->withErrors(['login' => 'Neplatné přihlašovací údaje.'])
                ->withInput();
        }

        
        $request->session()->regenerate();

        // dashboard
        return redirect()->intended('/dashboard');
    }
}
