<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\RoleMiddleware; 
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Auth\AuthenticationException;


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
        
        // ERROR HANDLING

        $exceptions->renderable(function (HttpException $e, $request) {

            // abort
            if ($e->getStatusCode() === 419) {
                return back()->with('info', 'Platnost stránky vypršela.');
            }
            // 403 Forbidden Handler 
            if ($e->getStatusCode() === 403) {
                
                // came from a site (via button/link)
                if ($request->headers->has('referer') && 
                    str_starts_with($request->header('referer'), $request->root())) {
                    
                    return back()->with('error', 'Nemáte oprávnění pro tuto akci.');
                }
                
                //  URL
                return redirect()
                    ->route('dashboard')
                    ->with('error', 'Nemáte oprávnění pro přístup k této stránce.');
            }
        });
        // 419 – CSRF token mismatch
            $exceptions->renderable(function (TokenMismatchException $e, $request) {
            return back()->with('info','Platnost stránky vypršela.');
        });
        // authentication expired
        $exceptions->renderable(function (AuthenticationException $e, $request) {
            return redirect()
                ->route('login')
                ->with('error', 'Relace vypršela. Přihlas se znova.');
        });

        // 404 – record not found in DB (/topics/200)

        $exceptions->renderable(function (NotFoundHttpException $e, $request) {

            if ($request->headers->has('referer') &&
                str_starts_with($request->header('referer'), $request->root())) {
                return back()->with('error', 'Záznam nebyl nalezen.');
            }

            return match (true) {
                
                $request->is('topics/*/campaigns/*') => redirect()
                    ->route('topics.index') 
                    ->with('error', 'Požadovaná kampaň neexistuje.'),

                // concrete URL
                $request->is('topics/*') => redirect()
                    ->route('topics.index')
                    ->with('error', 'Požadovaný topic neexistuje.'),

                // Default fallback
                default => redirect()
                    ->route('dashboard')
                    ->with('error', 'Požadovaná stránka neexistuje.'),
            };
        });
        
    })->create();