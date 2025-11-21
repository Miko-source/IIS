<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/workspace', [DashboardController::class, 'workspace'])->name('workspace');
    Route::post('/workspace/{activityUser}/report', [DashboardController::class, 'submitReport'])->name('workspace.report');
});

Route::middleware(['auth', 'role_at_least:campaign_manager'])->group(function () {
    Route::get('/dashboard/campaigns', [DashboardController::class, 'campaigns'])->name('dashboard.campaigns');
    Route::get('/dashboard/campaigns/{campaign}', [DashboardController::class, 'campaignDetail'])->name('dashboard.campaigns.show');
});

Route::delete('/dashboard/steps/{step}', [DashboardController::class, 'deleteStep'])
    ->middleware(['auth', 'role_at_least:campaign_manager'])
    ->name('dashboard.steps.delete');
