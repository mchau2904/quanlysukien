<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureUserRole; // ⬅️ THÊM DÒNG NÀY

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Đăng ký alias cho middleware kiểm tra role
        $middleware->alias([
            'role' => EnsureUserRole::class, // dùng trong routes: ->middleware(['auth','role:admin'])
        ]);

        // (Tuỳ chọn) nếu muốn thêm vào nhóm 'web' hay 'api' có thể dùng:
        // $middleware->web(append: [
        //     // \App\Http\Middleware\Something::class,
        // ]);
        // $middleware->api(append: [
        //     // \App\Http\Middleware\SomethingElse::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
