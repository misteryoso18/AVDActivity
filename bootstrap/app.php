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
    ->withMiddleware(function (Middleware $middleware): void {


    // global middleware
    $middleware->append(\App\Http\Middleware\PromotionMW::class);

    //middleware group
    $middleware->group('group',[
        \App\Http\Middleware\MiddlewareOne::class,
        \App\Http\Middleware\MiddlewareTwo::class,
        \App\Http\Middleware\DownForMaintnanceMW::class
        ]);

        //route middleware
    $middleware->alias([
        'maintenance' => \App\Http\Middleware\DownForMaintnanceMW::class,
        'checklogin' => \App\Http\Middleware\CheckLogin::class,
        'role' => \App\Http\Middleware\CheckRole::class,
        'auth.redirect' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
