<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignStepController;

Route::middleware(['auth', 'role_at_least:worker'])
    ->prefix('campaigns/{campaign}/steps')
    ->group(function () {
        Route::get('/', [CampaignStepController::class, 'index'])->name('campaign.steps.index');
        Route::get('/create', [CampaignStepController::class, 'create'])->name('campaign.steps.create');
        Route::post('/', [CampaignStepController::class, 'store'])->name('campaign.steps.store');
        Route::get('/{step}', [CampaignStepController::class, 'show'])->name('campaign.steps.show');
        Route::get('/{step}/edit', [CampaignStepController::class, 'edit'])->name('campaign.steps.edit');
        Route::put('/{step}', [CampaignStepController::class, 'update'])->name('campaign.steps.update');
        Route::delete('/{step}', [CampaignStepController::class, 'destroy'])->name('campaign.steps.destroy');
        Route::patch('/{step}/complete', [CampaignStepController::class, 'markComplete'])
            ->name('campaigns.steps.complete');
        Route::patch('/{step}/uncomplete', [CampaignStepController::class, 'markIncomplete'])
            ->name('campaigns.steps.uncomplete');
    });
