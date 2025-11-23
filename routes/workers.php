<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignWorkerController;

// jen admin + campaign manager
Route::middleware(['auth', 'role_at_least:campaign_manager'])->group(function () {

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

    Route::patch('/campaigns/{campaign}/steps/{step}/coordinator', 
        [CampaignWorkerController::class, 'updateCoordinator'])
        ->name('campaigns.steps.coordinator.update');

    Route::patch('/campaigns/{campaign}/manager', 
        [CampaignWorkerController::class, 'updateManager'])
        ->name('campaigns.manager.update');

});
