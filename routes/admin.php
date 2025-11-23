<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TopicController;

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // odstraníme create & store ze seznamu
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('topics', TopicController::class);
    });

