<?php

use App\Http\Middleware\EnsureAdminAccess;
use App\Http\Middleware\EnsureAdminModuleAccess;
use App\Http\Middleware\RecordPublicVisit;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.access' => EnsureAdminAccess::class,
            'admin.module' => EnsureAdminModuleAccess::class,
            'public.visit' => RecordPublicVisit::class,
        ]);
        $middleware->redirectUsersTo(function (Request $request): string {
            $user = $request->user();

            if ($user && ! $user->hasVerifiedEmail()) {
                return route('verification.notice');
            }

            if (in_array($user?->role?->slug, ['super-admin', 'admin', 'editor', 'hrd', 'marketing'], true)) {
                return route('admin.dashboard');
            }

            return route('home');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
