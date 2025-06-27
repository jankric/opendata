<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        // Middleware aliases
        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'cors' => \App\Http\Middleware\CorsMiddleware::class,
            'api.version' => \App\Http\Middleware\ApiVersionMiddleware::class,
            'api.track' => \App\Http\Middleware\TrackApiUsageMiddleware::class,
            'json.validate' => \App\Http\Middleware\ValidateJsonMiddleware::class,
        ]);

        // Throttle configuration
        $middleware->throttleApi('api');
        
        // CORS for API routes
        $middleware->group('api', [
            \App\Http\Middleware\CorsMiddleware::class,
            \App\Http\Middleware\ApiVersionMiddleware::class,
            \App\Http\Middleware\TrackApiUsageMiddleware::class,
            \App\Http\Middleware\ValidateJsonMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();