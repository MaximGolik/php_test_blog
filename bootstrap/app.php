<?php

use App\Http\Middleware\CheckToken;
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
        $middleware->appendToGroup('check-token', [CheckToken::class]);
    })
    //todo тут обрабатывать только кастомные исключения, посмотреть можно ли обрабатывать через middleware
    ->withExceptions(function (Exceptions $exceptions) {
//        $exceptions->renderable(function (Throwable $e, Request $request) {
//            $statusCode = $e->getCode() > 0 ? $e->getCode() : 500;
//            if ($request->is('api/*')) {
//                return response()->json([
//                    'message' => $e->getMessage(),
//                    'code' => $statusCode,
//                ], $statusCode);
//            }
//        });
    })->create();
