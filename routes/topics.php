<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopicPublicController;

Route::middleware(['auth', 'role_at_least:worker'])->group(function () {
    Route::get('/topics', [TopicPublicController::class, 'index'])->name('topics.index');
    Route::get('/topics/{topic}', [TopicPublicController::class, 'show'])->name('topics.show');
});
