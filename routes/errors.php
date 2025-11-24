<?php

use Illuminate\Support\Facades\Route;

// 404 non-existing page handler
Route::fallback(function () {
    // guest, guest URL
    if (!auth()->check()) {
        return redirect()
            ->route('login')
            ->with('error', 'Požadovaná stránka neexistuje.');
    }
    
    // prihlaseny uzivatel
    if (request()->headers->has('referer') && 
        str_starts_with(request()->header('referer'), request()->root())) {
        
        return back()->with('error', 'Stránka nebyla nalezena.');
    }
    // prihlaseni uzivatel URL
    return redirect()
        ->route('dashboard')
        ->with('error', 'Požadovaná stránka neexistuje.');
});