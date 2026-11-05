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
        $middleware->redirectTo(
            guests: function (\Illuminate\Http\Request $request) {
                // Saat session habis atau belum login, selalu arahkan ke login OPD (/login)
                return route('login');
            },
            users: function () {
                if (auth()->check() && auth()->user()->isSuperAdmin()) {
                    return route('admin.dashboard');
                }
                return route('opd.dashboard');
            }
        );

        $middleware->validateCsrfTokens(except: [
            'logout',
            '/logout',
            'opd/logout',
            '/opd/logout',
            'admin/logout',
            '/admin/logout',
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminOnly::class,
            'opd' => \App\Http\Middleware\OpdOnly::class,
            'session.timeout' => \App\Http\Middleware\AutoLogoutTimeout::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Tangani TokenMismatchException (Error 419 Page Expired) saat session habis pada POST / AJAX
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            $targetRoute = route('login');

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sesi Anda telah berakhir. Silakan login kembali.',
                    'redirect' => $targetRoute,
                ], 401);
            }

            return redirect()->to($targetRoute);
        });

        // Tangani HttpException 419 (Page Expired) umum
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) {
            if ($e->getStatusCode() === 419) {
                $targetRoute = route('login');

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Sesi Anda telah berakhir. Silakan login kembali.',
                        'redirect' => $targetRoute,
                    ], 401);
                }

                return redirect()->to($targetRoute);
            }
        });
    })->create();
