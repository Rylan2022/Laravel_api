<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     */
    protected $middleware = [
        // Trust proxies
        \App\Http\Middleware\TrustProxies::class,

        // Handle CORS
        \Illuminate\Http\Middleware\HandleCors::class,

        // Prevent requests during maintenance mode
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,

        // Validate POST size
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,

        // Trim input strings
        \App\Http\Middleware\TrimStrings::class,

        // Convert empty strings to null
        \App\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * Route middleware groups.
     */
    protected $middlewareGroups = [
        'web' => [
            // Encrypt cookies
            \App\Http\Middleware\EncryptCookies::class,

            // Add queued cookies to response
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,

            // Start session
            \Illuminate\Session\Middleware\StartSession::class,

            // Share errors from session with views
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,

            // CSRF protection
            \App\Http\Middleware\VerifyCsrfToken::class,

            // Route binding
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // Throttle requests
            'throttle:api',

            // Route binding
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * Route middleware (can be assigned individually).
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'jwt.session' => \App\Http\Middleware\JwtAuthMiddle::class,

    ];
}
