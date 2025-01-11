<?php

use App\Http\Middleware\CheckToken;
use App\Http\Middleware\HandleExceptionMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->prepend(HandleExceptionMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // чтобы исключения в laravel 11 обрабатывались в посреднике
        $exceptions->renderable(function (Throwable $e) {
            throw $e;
        });
    })->create();
