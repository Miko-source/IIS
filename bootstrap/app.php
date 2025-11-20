<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\RoleMiddleware; 
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Symfony\Component\HttpKernel\Exception\HttpException; 

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'role_at_least' => \App\Http\Middleware\RoleAtLeastMiddleware::class,

        ]);

        // Web middleware stack
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        
            // 403 Forbidden Handler 
            $exceptions->renderable(function (HttpException $e, $request) {
                if ($e->getStatusCode() === 403) {
                    
                    // Pokud přišel z jiné stránky (klikl na odkaz/tlačítko)
                    if ($request->headers->has('referer') && 
                        str_starts_with($request->header('referer'), $request->root())) {
                        
                        return back()->with('error', 'Nemáte oprávnění pro tuto akci.');
                    }
                    
                    // Přímé zadání URL
                    return redirect()
                        ->route('dashboard')
                        ->with('error', 'Nemáte oprávnění pro přístup k této stránce.');
                }
            });

        })->create();

