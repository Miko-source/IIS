<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;

Route::middleware(['auth'])->group(function () {

    Route::get('/campaigns/{campaign}/steps/{step}/activities/create', [ActivityController::class, 'create'])->name('activities.create');
    Route::post('/campaigns/{campaign}/steps/{step}/activities', [ActivityController::class, 'store'])->name('activities.store');
    Route::get('/campaigns/{campaign}/steps/{step}/activities/{activity}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
    Route::put('/campaigns/{campaign}/steps/{step}/activities/{activity}', [ActivityController::class, 'update'])->name('activities.update');
    Route::delete('/campaigns/{campaign}/steps/{step}/activities/{activity}', [ActivityController::class, 'destroy'])->name('activities.destroy');
    //zmenaaa
    Route::post('/activities/{activity}/confirm-activity',
    [ActivityController::class, 'confirm_activity']
)->name('activities.confirm_activity');

        Route::post(
        '/activities/{activity}/workers',
        [ActivityController::class, 'addWorker']
    )->name('activities.workers.add');
});

// signup / confirm / leave
Route::middleware(['auth'])->group(function () {
    Route::post('/activities/{activity}/signup', [ActivityController::class, 'signup'])->name('activities.signup');
    Route::delete('/activities/{activity}/leave', [ActivityController::class, 'leave'])->name('activities.leave');

    Route::patch('/activity-users/{activityUser}/confirm', [ActivityController::class, 'confirm'])->name('activityUsers.confirm');
    Route::patch('/activity-users/{activityUser}/reject', [ActivityController::class, 'reject'])->name('activityUsers.reject');
});
