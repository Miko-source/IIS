<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\TopicPublicController;
use App\Http\Controllers\CampaignController;


// ÚVODNÍ STRÁNKA
Route::get('/', function () {
    return view('home');
})->name('home');


// AUTH ROUTES
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::post('/logout', [LogoutController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// DASHBOARD (jen pro přihlášené)
Route::get('/dashboard', function () {
    return view('dashboard');
})
->middleware('auth')
->name('dashboard');


// PROFIL UŽIVATELE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


// ADMIN – SPRÁVA UŽIVATELŮ
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('users', AdminUserController::class)->except(['show', 'create', 'store']);
    });

Route::post('/admin/users/{user}/edit', [UserController::class, 'update'])
    ->name('admin.users.update')
    ->middleware(['auth', 'admin']);

 

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('topics', TopicController::class);
    });
//zobrazeni temat
Route::get('/topics', [TopicPublicController::class, 'index'])->name('topics.index');
Route::get('/topics/{topic}', [TopicPublicController::class, 'show'])->name('topics.show');

// Kampaně k tématu
Route::prefix('topics/{topic}')->group(function () {
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('topics.campaigns.index');
    Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('topics.campaigns.show');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('topics.campaigns.create');
        Route::post('/campaigns', [CampaignController::class, 'store'])->name('topics.campaigns.store');
    });

Route::get('topics/{topic}/campaigns/{campaign}', [CampaignController::class, 'show'])
    ->name('topics.campaigns.show');
});