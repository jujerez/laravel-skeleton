<?php

use App\Helpers\AjaxForm;
use App\Http\Middleware\ClientAccess;
use App\Http\Middleware\DashboardAccess;
use App\Http\Middleware\SuperadminAccess;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'dashboard.access' => DashboardAccess::class,
            'client.access' => ClientAccess::class,
            'superadmin.access' => SuperadminAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->expectsJson()) {
                return AjaxForm::validation($e->errors())->jsonResponse(422);
            }
        });
    })->create();
