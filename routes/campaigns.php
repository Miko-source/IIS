<?php
/**
 * ---------------------------------------------------------
 * Autor:  Martin Bureš
 * Login:  xbures38
 * ---------------------------------------------------------
 */

use App\Http\Controllers\CampaignController;
use Illuminate\Support\Facades\Route;
// Kampaně k tématu
Route::prefix('topics/{topic}')->group(function () {
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('topics.campaigns.index');
    Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('topics.campaigns.show');

    Route::middleware(['auth', 'role_at_least:campaign_manager'])->group(function () {
        Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('topics.campaigns.create');
        Route::post('/campaigns', [CampaignController::class, 'store'])->name('topics.campaigns.store');

        Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])
            ->name('topics.campaigns.edit');

        Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])
            ->name('topics.campaigns.update');

        Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])
            ->name('topics.campaigns.destroy');
    });
});
