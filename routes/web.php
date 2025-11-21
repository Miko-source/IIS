<?php

use Illuminate\Support\Facades\Route;

// Home
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('home');
})->name('home');

// Include route files
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/topics.php';
require __DIR__.'/campaigns.php';
require __DIR__.'/steps.php';
require __DIR__.'/activities.php';
require __DIR__.'/workers.php';
require __DIR__.'/dashboard.php';
require __DIR__.'/profile.php';