<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CampaignStepController;



// Home
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('home');
})->name('home');

// Include route file
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/topics.php';
require __DIR__.'/campaigns.php';
require __DIR__.'/steps.php';
require __DIR__.'/activities.php';
require __DIR__.'/workers.php';
require __DIR__.'/dashboard.php';
require __DIR__.'/profile.php';
require __DIR__.'/errors.php';

Route::get('/campaigns/{campaign}/steps/{step}/activities/{activity}', 
    [ActivityController::class, 'show']
)->name('activities.show');


Route::patch(
    '/campaigns/{campaign}/steps/{step}/order',
    [CampaignStepController::class, 'updateOrder']
)->name('campaign.steps.order');
