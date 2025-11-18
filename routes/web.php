<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;

// ÚVODNÍ STRÁNKA – tvoje view s kartičkami + login modal
Route::get('/', function () {
    return view('home');
})->name('home');

// AUTH ROUTES (Mikov backend)

// Login
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Registrace
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// Logout
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Dashboard (jen pro přihlášené)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');
