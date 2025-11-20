<?php
/**
 * ---------------------------------------------------------
 * Autor:  Martin Bureš
 * Login:  xbures38
 * ---------------------------------------------------------
 */

use App\Http\Controllers\Campaign\CampaignController;
use App\Http\Controllers\Campaign\CampaignManagerController;
use Illuminate\Support\Facades\Route;

// Kampaně k tématu
Route::prefix('topics/{topic}')->group(function () {
    Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('topics.campaigns.show');

    Route::middleware(['auth', 'role_at_least:campaign_manager'])
        ->prefix('campaigns')
        ->name('topics.campaigns.')
        ->group(function () {
            Route::get('/create', [CampaignController::class, 'create'])->name('create');
            Route::post('/', [CampaignController::class, 'store'])->name('store');
            Route::get('/{campaign}', [CampaignController::class, 'show'])->name('show');
            
            Route::get('/{campaign}/edit', [CampaignController::class, 'edit'])->name('edit');
            Route::put('/{campaign}', [CampaignController::class, 'update'])->name('update');
            Route::delete('/{campaign}', [CampaignController::class, 'destroy'])->name('destroy');
        });

        // Správa správců kampaní - jen pro adminy
        Route::patch('/campaigns/{campaign}/manager', [CampaignManagerController::class, 'update'])
            ->name('topics.campaigns.manager.update');
        Route::delete('/campaigns/{campaign}/manager', [CampaignManagerController::class, 'destroy'])
            ->name('topics.campaigns.manager.destroy');
});
