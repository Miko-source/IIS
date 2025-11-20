<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TopicController;

use App\Http\Controllers\TopicPublicController;
use App\Http\Controllers\Campaign\CampaignController;
use App\Http\Controllers\CampaignStepController;
use App\Http\Controllers\ActivityController;  
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CampaignWorkerController;


// -------------------------------------------------------------
// Uvodni stranka – verejna homepage
// -------------------------------------------------------------
Route::get('/', function () {
    return view('home');
})->name('home');


// -------------------------------------------------------------
// Autentizace – login, registrace, logout
// -------------------------------------------------------------
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::post('/logout', [LogoutController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// -------------------------------------------------------------
// Dashboard uzivatele
// -------------------------------------------------------------
Route::get('/dashboard', function () {
    return view('dashboard');
})
->middleware('auth')
->name('dashboard');


// -------------------------------------------------------------
// Profil uzivatele – editace a ulozeni
// -------------------------------------------------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


// -------------------------------------------------------------
// Admin – sprava uzivatelu
// -------------------------------------------------------------
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('users', AdminUserController::class)->except(['show', 'create', 'store']);
    });


// -------------------------------------------------------------
// Admin – sprava temat
// -------------------------------------------------------------
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('topics', TopicController::class);
    });


// -------------------------------------------------------------
// Verejna temata – vypis a detail
// -------------------------------------------------------------
Route::get('/topics', [TopicPublicController::class, 'index'])->name('topics.index');
Route::get('/topics/{topic}', [TopicPublicController::class, 'show'])->name('topics.show');


// -------------------------------------------------------------
// Kampane – includnute z extra souboru
// -------------------------------------------------------------
require __DIR__.'/campaigns.php';


// -------------------------------------------------------------
// Kroky kampane (steps)
// -------------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // vypis kroku kampane
    Route::get('/campaigns/{campaign}/steps',
        [CampaignStepController::class, 'index'])
        ->name('campaign.steps.index');

    // vytvoreni kroku
    Route::get('/campaigns/{campaign}/steps/create',
        [CampaignStepController::class, 'create'])
        ->name('campaign.steps.create');

    // ulozeni noveho kroku
    Route::post('/campaigns/{campaign}/steps',
        [CampaignStepController::class, 'store'])
        ->name('campaign.steps.store');

    // detail kroku
    Route::get('/campaigns/{campaign}/steps/{step}',
        [CampaignStepController::class, 'show'])
        ->name('campaign.steps.show');

    // editace kroku
    Route::get('/campaigns/{campaign}/steps/{step}/edit',
        [CampaignStepController::class, 'edit'])
        ->name('campaign.steps.edit');

    // ulozeni editace
    Route::put('/campaigns/{campaign}/steps/{step}',
        [CampaignStepController::class, 'update'])
        ->name('campaign.steps.update');
});


// -------------------------------------------------------------
// Aktivity – CRUD + akce pracovníku
// -------------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // vytvoreni aktivity
    Route::get('/campaigns/{campaign}/steps/{step}/activities/create',
        [ActivityController::class, 'create'])
        ->name('activities.create');

    // ulozeni aktivity
    Route::post('/campaigns/{campaign}/steps/{step}/activities',
        [ActivityController::class, 'store'])
        ->name('activities.store');

    // editace
    Route::get('/campaigns/{campaign}/steps/{step}/activities/{activity}/edit',
        [ActivityController::class, 'edit'])
        ->name('activities.edit');

    // ulozeni editace
    Route::put('/campaigns/{campaign}/steps/{step}/activities/{activity}',
        [ActivityController::class, 'update'])
        ->name('activities.update');

    // smazani
    Route::delete('/campaigns/{campaign}/steps/{step}/activities/{activity}',
        [ActivityController::class, 'destroy'])
        ->name('activities.destroy');
});

// registrace na aktivitu
Route::post('/activities/{activity}/signup', 
    [ActivityController::class, 'signup'])
    ->middleware('auth')
    ->name('activities.signup');

// potvrzeni pracovnika koordinatorem
Route::post('/activities/{activity}/confirm/{user}', 
    [ActivityController::class, 'confirmWorker'])
    ->middleware('role:coordinator')
    ->name('activities.confirm');

// odhlaseni z aktivity
Route::delete('/activities/{activity}/leave', 
    [ActivityController::class, 'leave'])
    ->middleware('auth')
    ->name('activities.leave');


// dashboard – vypis zadosti
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// potvrzeni ucasti v aktivitach
Route::patch('/activity-users/{activityUser}/confirm',
    [ActivityController::class, 'confirm'])
    ->name('activityUsers.confirm')
    ->middleware('auth');

// odmitnuti ucasti
Route::patch('/activity-users/{activityUser}/reject',
    [ActivityController::class, 'reject'])
    ->name('activityUsers.reject')
    ->middleware('auth');


// -------------------------------------------------------------
// Sprava pracovniku kampane
// -------------------------------------------------------------
Route::get('/campaigns/manage-workers', 
    [CampaignWorkerController::class, 'selectTopic'])
    ->name('campaigns.manage');

Route::get('/campaigns/{campaign}/workers', 
    [CampaignWorkerController::class, 'manageWorkers'])
    ->name('campaigns.workers');

Route::post('/campaigns/{campaign}/workers', 
    [CampaignWorkerController::class, 'addWorker'])
    ->name('campaigns.workers.add');

Route::delete('/campaigns/{campaign}/workers/{user}', 
    [CampaignWorkerController::class, 'removeWorker'])
    ->name('campaigns.workers.remove');


// -------------------------------------------------------------
// Workspace pracovnika – reporty
// -------------------------------------------------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/workspace', [DashboardController::class, 'workspace'])->name('workspace');
    Route::post('/workspace/{activityUser}/report', [DashboardController::class, 'submitReport'])->name('workspace.report');
});


// -------------------------------------------------------------
// Dashboard kampani pro spravce
// -------------------------------------------------------------
Route::middleware(['auth', 'role_at_least:campaign_manager'])->group(function () {

    // vypis vsech kampani
    Route::get('/dashboard/campaigns', [DashboardController::class, 'campaigns'])
        ->name('dashboard.campaigns');

    // detail kampane
    Route::get('/dashboard/campaigns/{campaign}', [DashboardController::class, 'campaignDetail'])
        ->name('dashboard.campaigns.show');
});

// smazani kroku pres dashboard
Route::delete('/dashboard/steps/{step}', 
    [DashboardController::class, 'deleteStep'])
    ->name('dashboard.steps.delete')
    ->middleware(['auth', 'role_at_least:campaign_manager']);


// -------------------------------------------------------------
// Zmeny koordinatora a spravce kampane
// -------------------------------------------------------------
Route::patch('/campaigns/{campaign}/steps/{step}/coordinator',
    [CampaignWorkerController::class, 'updateCoordinator'])
    ->name('campaigns.steps.coordinator.update');

Route::patch('/campaigns/{campaign}/manager',
    [CampaignWorkerController::class, 'updateManager'])
    ->name('campaigns.manager.update');
