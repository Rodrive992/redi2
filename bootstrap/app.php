<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckDependencia;
use App\Http\Middleware\UsuarioDgp;
use App\Http\Middleware\UsuarioExterno;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'dependencia' => \App\Http\Middleware\CheckDependencia::class,
            'UsuarioDgp' => \App\Http\Middleware\UsuarioDgp::class,
            'UsuarioExterno' => \App\Http\Middleware\UsuarioExterno::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();