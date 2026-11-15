<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Cookie-based Sanctum auth for first-party SPA requests (Next.js).
        $middleware->statefulApi();
        $middleware->throttleApi(); // 'api' limiter, see AppServiceProvider

        // No login page here: customers sign in on the website (Filament has its own).
        $middleware->redirectGuestsTo(fn () => rtrim((string) config('services.storefront.url'), '/').'/login');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // API clients always get JSON errors, even without an Accept header.
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson()
        );
    })->create();
