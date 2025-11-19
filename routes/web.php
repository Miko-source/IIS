<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TopicController;

use App\Http\Controllers\TopicPublicController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CampaignStepController;
use App\Http\Controllers\ActivityController;  
use App\Http\Controllers\DashboardController;


// -------------------------------------------------------------
// Uvodni stranka
// -------------------------------------------------------------
Route::get('/', function () {
    return view('home');
})->name('home');


// -------------------------------------------------------------
// AUTH
// -------------------------------------------------------------
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::post('/logout', [LogoutController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// -------------------------------------------------------------
// DASHBOARD 
// -------------------------------------------------------------
Route::get('/dashboard', function () {
    return view('dashboard');
})
->middleware('auth')
->name('dashboard');


// -------------------------------------------------------------
// PROFIL
// -------------------------------------------------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


// -------------------------------------------------------------
// Sprava Uzivatelu
// -------------------------------------------------------------
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('users', AdminUserController::class)->except(['show', 'create', 'store']);
    });


// -------------------------------------------------------------
// Sprava Temat
// -------------------------------------------------------------
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('topics', TopicController::class);
    });


// -------------------------------------------------------------
// Temata
// -------------------------------------------------------------
Route::get('/topics', [TopicPublicController::class, 'index'])->name('topics.index');
Route::get('/topics/{topic}', [TopicPublicController::class, 'show'])->name('topics.show');


require __DIR__.'/campaigns.php';


// -------------------------------------------------------------
// STEPS
// -------------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // kroky dane kampne 
    Route::get('/campaigns/{campaign}/steps',
        [CampaignStepController::class, 'index'])
        ->name('campaign.steps.index');

    // create
    Route::get('/campaigns/{campaign}/steps/create',
        [CampaignStepController::class, 'create'])
        ->name('campaign.steps.create');

    // uloz novy
    Route::post('/campaigns/{campaign}/steps',
        [CampaignStepController::class, 'store'])
        ->name('campaign.steps.store');

    // detail
    Route::get('/campaigns/{campaign}/steps/{step}',
        [CampaignStepController::class, 'show'])
        ->name('campaign.steps.show');

    // edit
    Route::get('/campaigns/{campaign}/steps/{step}/edit',
        [CampaignStepController::class, 'edit'])
        ->name('campaign.steps.edit');

    // uloz
    Route::put('/campaigns/{campaign}/steps/{step}',
        [CampaignStepController::class, 'update'])
        ->name('campaign.steps.update');

});

// -------------------------------------------------------------
// ACTIVITY ROUTES
// -------------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // create
    Route::get('/campaigns/{campaign}/steps/{step}/activities/create',
        [ActivityController::class, 'create'])
        ->name('activities.create');

    // uloz aktivitu
    Route::post('/campaigns/{campaign}/steps/{step}/activities',
        [ActivityController::class, 'store'])
        ->name('activities.store');

    // edit
    Route::get('/campaigns/{campaign}/steps/{step}/activities/{activity}/edit',
        [ActivityController::class, 'edit'])
        ->name('activities.edit');

    // edit uloz
    Route::put('/campaigns/{campaign}/steps/{step}/activities/{activity}',
        [ActivityController::class, 'update'])
        ->name('activities.update');

    // smazat
    Route::delete('/campaigns/{campaign}/steps/{step}/activities/{activity}',
        [ActivityController::class, 'destroy'])
        ->name('activities.destroy');
});

Route::post('/activities/{activity}/signup', 
    [ActivityController::class, 'signup'])
    ->middleware('auth')
    ->name('activities.signup');

Route::post('/activities/{activity}/confirm/{user}', 
    [ActivityController::class, 'confirmWorker'])
    ->middleware('role:coordinator')
    ->name('activities.confirm');
Route::delete('/activities/{activity}/leave', 
    [ActivityController::class, 'leave'])
    ->middleware('auth')
    ->name('activities.leave');


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');
