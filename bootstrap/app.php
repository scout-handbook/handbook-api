<?php

declare(strict_types=1);

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Skaut\HandbookAPI\v1_0\Exception\Exception;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders()
    ->withRouting(api: __DIR__.'/../routes/api.php', apiPrefix: '', commands: __DIR__.'/../routes/console.php')
    ->withMiddleware(static function (Middleware $middleware): void {
        $middleware->use([
            AddQueuedCookiesToResponse::class,
            ValidatePostSize::class,
            TrimStrings::class,
            ConvertEmptyStringsToNull::class,
        ]);
    })
    ->withExceptions(static function (Exceptions $exceptions): void {
        $exceptions->render(static fn (Exception $e) => response()->json($e->handle(), $e->handle()['status']));
    })->create();
