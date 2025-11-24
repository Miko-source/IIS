<?php

use App\Http\Controllers\Campaign\CampaignController;
use App\Http\Controllers\Campaign\CampaignManagerController;
use Illuminate\Support\Facades\Route;

// Správa kampaní
Route::prefix('topics/{topic}')->group(function () {
    // Detail kampane
    Route::middleware(['auth', 'role_at_least:worker'])
        ->prefix('campaigns')
        ->name('topics.campaigns.')
        ->group(function () {
            Route::get('/{campaign}', [CampaignController::class, 'show'])
                ->whereNumber('campaign')
                ->name('show');
        });

    // Uprava kampane
    Route::middleware(['auth', 'role_at_least:campaign_manager'])
        ->prefix('campaigns')
        ->name('topics.campaigns.')
        ->group(function () {
            Route::get('/{campaign}/edit', [CampaignController::class, 'edit'])
                ->whereNumber('campaign')
                ->name('edit');
            Route::put('/{campaign}', [CampaignController::class, 'update'])
                ->whereNumber('campaign')
                ->name('update');
        });

    // Vytvoreni a smazani kampane
    Route::middleware(['auth', 'role_at_least:admin'])
        ->prefix('campaigns')
        ->name('topics.campaigns.')
        ->group(function () {
            Route::get('/create', [CampaignController::class, 'create'])->name('create');
            Route::post('/', [CampaignController::class, 'store'])->name('store');
            Route::delete('/{campaign}', [CampaignController::class, 'destroy'])
                ->whereNumber('campaign')
                ->name('destroy');
        });

    // Sprava spravcu kampani
    Route::middleware(['auth', 'role_at_least:admin'])
        ->prefix('campaigns/{campaign}/manager')
        ->name('topics.campaigns.manager.')
        ->group(function () {
            Route::patch('/', [CampaignManagerController::class, 'update'])
                ->whereNumber('campaign')
                ->name('update');

            Route::delete('/', [CampaignManagerController::class, 'destroy'])
                ->whereNumber('campaign')
                ->name('destroy');
        });
});
