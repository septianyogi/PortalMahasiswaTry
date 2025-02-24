<?php

use App\Http\Middleware\DebugbarHeaders;
use App\Http\Middleware\is_admin;
use App\Http\Middleware\is_dosen;
use App\Http\Middleware\is_student;
use Illuminate\Console\Scheduling\Schedule;
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
        $middleware->alias([
            'debugBar' => DebugbarHeaders::class,
            'is_admin' => is_admin::class,
            'is_student' => is_student::class,
            'is_dosen' => is_dosen::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->booted(function ($app) {
        // Tambahkan scheduler
        $schedule = $app->make(Schedule::class);

        // Menjalankan command 'attendance:cleanup' setiap 5 menit
        $schedule->command('attendance:cleanup')->everyTwoMinutes();
    })
    ->create();
