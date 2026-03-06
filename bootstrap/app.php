<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',        // API routes for Vue.js AJAX calls
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php', // Broadcast channel authorization
        health: '/up',
        then: function (): void {
            Route::middleware('web')->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register middleware aliases
        $middleware->alias([
            'banned' => \App\Http\Middleware\BannedUserCheck::class,
            'brute.force' => \App\Http\Middleware\BruteForceProtection::class,
            'admin.active' => \App\Http\Middleware\AdminActiveCheck::class,
        ]);

        // Append banned-user check to web middleware group
        $middleware->web(append: [
            \App\Http\Middleware\BannedUserCheck::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
