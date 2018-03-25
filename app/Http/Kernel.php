<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{

    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        //\App\Http\Middleware\TrimStrings::class, // Deactivated. We do not want POST fields to be trimmed via trim()
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \Contentify\Middleware\HttpsRedirect::class, // Custom middleware from Contentify

            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class, // Deactivated er Laravel's default
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,

            \Contentify\Middleware\UpdateUser::class, // Custom middleware from Contentify
            \App\Http\Middleware\VerifyAdminAccess::class, // Custom middleware from Contentify
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'      => \App\Http\Middleware\Authenticate::class,
        'bindings'  => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'guest'     => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle'  => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    ];

}
