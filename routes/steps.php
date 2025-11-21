<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignStepController;

Route::middleware(['auth'])->group(function () {
    Route::get('/campaigns/{campaign}/steps', [CampaignStepController::class, 'index'])->name('campaign.steps.index');
    Route::get('/campaigns/{campaign}/steps/create', [CampaignStepController::class, 'create'])->name('campaign.steps.create');
    Route::post('/campaigns/{campaign}/steps', [CampaignStepController::class, 'store'])->name('campaign.steps.store');
    Route::get('/campaigns/{campaign}/steps/{step}', [CampaignStepController::class, 'show'])->name('campaign.steps.show');
    Route::get('/campaigns/{campaign}/steps/{step}/edit', [CampaignStepController::class, 'edit'])->name('campaign.steps.edit');
    Route::put('/campaigns/{campaign}/steps/{step}', [CampaignStepController::class, 'update'])->name('campaign.steps.update');
    Route::delete('/campaigns/{campaign}/steps/{step}', [CampaignStepController::class, 'destroy'])->name('campaign.steps.destroy');
    Route::patch('campaigns/{campaign}/steps/{step}/complete',
    [\App\Http\Controllers\CampaignStepController::class, 'markComplete'])
    ->name('campaigns.steps.complete');

});
