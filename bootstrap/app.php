<?php

/*
|--------------------------------------------------------------------------
| Pretend DELETE request method
|--------------------------------------------------------------------------
|
| Since Laravel can't create URLs with request methods,
| it needs a little help. We use the GET-parameter 'method' to 
| pretend a DELETE request method.
| This has to be done before the App starts.
| (Of course, with JS and AJAX / hidden form sending DELETE is possible.)
| 
*/

if (isset($_GET['method']) and strtolower($_GET['method']) == 'delete') {
    $_SERVER['REQUEST_METHOD'] = 'DELETE';
}

/*
|--------------------------------------------------------------------------
| Permission Levels
|--------------------------------------------------------------------------
|
| Define constants for permission levels
| (part of Sentry's user permission system).
|
*/

if (! defined('PERM_READ')) {
    define('PERM_READ', 1);
    define('PERM_CREATE', 2);
    define('PERM_UPDATE', 3);
    define('PERM_DELETE', 4);   
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
