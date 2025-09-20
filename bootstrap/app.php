<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
        $exceptions->renderable(function (\Illuminate\Validation\ValidationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'code' => 422,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        //error intended
        $exceptions->renderable(function (\Exception $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'code' => 400,
                    'message' => $e->getMessage(),
                    'trace' => config('app.debug') ? collect($e->getTrace())->take(5) : null
                ], 400);
            }
        });

        // Handle all other unhandled exceptions (500)
        $exceptions->renderable(function (\Throwable $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'code' => 500,
                    'message' => 'Internal server error',
                    'error' => config('app.debug') ? $e->getMessage() : null,
                    'trace' => config('app.debug') ? collect($e->getTrace())->take(5) : null
                ], 500);
            }
        });
    })->create();
