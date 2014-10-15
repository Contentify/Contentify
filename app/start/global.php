<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

    app_path().'/commands',
    app_path().'/controllers',
    app_path().'/models',
    app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
    Log::error($exception);

    if (is_a($exception, 'MsgException')) {
        return Response::make(View::make('error_message', compact('exception')), 404);
    }

    if (! Config::Get('app.debug')) { // If we are in debug mode we do not want to override Laravel's error output
        if (is_a($exception, 'Illuminate\Database\Eloquent\ModelNotFoundException')) {
            return Response::make(View::make('error_not_found'), 404);
        }

        return Response::make(View::make('error'), 500);
    }
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
    return Response::make(View::make('maintenance'), 503);
});

/*
|--------------------------------------------------------------------------
| Tracking Execution Time
|--------------------------------------------------------------------------
|
| Log the app execution time
|
*/

App::finish(function() {
    if (Config::get('app.debug')) {
        echo "<script>console.log('App finish: ".round((microtime(true) - LARAVEL_START) * 1000, 3)." ms')</script>";
    }
});


/*
|--------------------------------------------------------------------------
| Require The Custom Validators File
|--------------------------------------------------------------------------
|
| This is the right place for custom validators.
|
*/

require app_path().'/validators.php';

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';

/*
|--------------------------------------------------------------------------
| Require Blade Extensions
|--------------------------------------------------------------------------
|
| This will load the blade_extensions file for the application. It's the
| right place for blade extensions.
|
*/

require app_path().'/blade_extensions.php';

/*
|--------------------------------------------------------------------------
| Require The Helpers File
|--------------------------------------------------------------------------
|
| Beside Laravel's very own helpers we may want to create our own.
| This file is the location to store these helper functions.
|
*/

require app_path().'/helpers.php';

/*
|--------------------------------------------------------------------------
| Require The Visitor File
|--------------------------------------------------------------------------
|
| Updates the global visitor statistics.
|
*/

require app_path().'/visitors.php';

/*
|--------------------------------------------------------------------------
| Permission Levels
|--------------------------------------------------------------------------
|
| Define constants for permisson levels 
| (part of Sentry's user permission system).
|
*/

const PERM_READ      = 1;
const PERM_CREATE    = 2;
const PERM_UPDATE    = 3;
const PERM_DELETE    = 4;

/*
|--------------------------------------------------------------------------
| Language Settings
|--------------------------------------------------------------------------
|
| Set the language for the user (also if not logged in)
|
*/

if (! Session::has('locale')) {
    if (user()) {
        Session::set('locale', user()->language->code);
        App::setLocale(Session::get('locale'));
    } else {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $clientLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            $languages = File::directories(app_path().'/lang');

            array_walk($languages, function(&$value, $key)
            {
                $value = basename($value);
            });

            if (in_array($clientLanguage, $languages)) {
                Session::set('locale', $clientLanguage);
            } else {
                Session::set('locale', Lang::getLocale());
            }
        } else {
            Session::set('locale', Lang::getLocale());
        }
    }
} else {
    App::setLocale(Session::get('locale'));
}

Carbon::setToStringFormat(trans('app.date_format'));