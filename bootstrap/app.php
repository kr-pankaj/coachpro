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
        $middleware->web(append: [
            \App\Http\Middleware\ResolveInstitute::class,
        ]);

        $middleware->alias([
            'check.subscription' => \App\Http\Middleware\CheckSubscription::class,
            'superadmin' => \App\Http\Middleware\SuperAdminOnly::class,
            'admin' => \App\Http\Middleware\AdminOnly::class,
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Redirect guests to global login or tenant login
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            $slug = $request->segment(1);
            if ($slug && \App\Models\Institute::where('slug', $slug)->exists()) {
                return route('login', ['slug' => $slug]);
            }
            return route('login.global');
        });

        // Redirect authenticated users away from guest pages (login/register)
        $middleware->redirectUsersTo(function () {
            $user = auth()->user();
            if ($user->role === 'superadmin') return route('superadmin.index');
            if ($user->institute) return route('dashboard', ['slug' => $user->institute->slug]);
            return route('login.global');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
