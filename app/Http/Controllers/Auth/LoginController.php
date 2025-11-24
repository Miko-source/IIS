<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        // pokud prihlasen presmeruj na dashboard
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
        
        return view('home', ['openLoginModal' => true]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        // fail prihlaseni
        if (!Auth::attempt($credentials)) {
            return back()
                ->withErrors(['login' => 'Neplatné přihlašovací údaje.'])
                ->with('openLoginModal', true)
                ->withInput();
        }

        
        $request->session()->regenerate();

        // dashboard
        return redirect()->intended('/dashboard');
    }
}
