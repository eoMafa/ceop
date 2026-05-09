<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->throttleApi();
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'permission'  => \App\Http\Middleware\CheckPermission::class,
            'user.ativo'  => \App\Http\Middleware\CheckUserAtivo::class,
            'session.timeout' => \App\Http\Middleware\SessionTimeout::class,
        ]);

        $middleware->appendToGroup('web', [
            \App\Http\Middleware\CheckUserAtivo::class,
            \App\Http\Middleware\SessionTimeout::class,
        ]);
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'permission' => \App\Http\Middleware\CheckPermission::class,
        ]);
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'permission'  => \App\Http\Middleware\CheckPermission::class,
            'user.ativo'  => \App\Http\Middleware\CheckUserAtivo::class,
        ]);

        // Aplica globalmente em todas as rotas autenticadas
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\CheckUserAtivo::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
