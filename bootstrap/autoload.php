<?php

define('LARAVEL_START', microtime(true));

/**
 * Translate the given message.
 * 
 * NOTE: This function cannot live inside the custom helpers file,
 * because it has to be loaded before the Laravel helper file.
 *
 * @param  string  $id
 * @param  array   $parameters
 * @param  string  $domain
 * @param  string  $locale
 * @return string
 */
function trans($id, $parameters = array(), $domain = 'messages', $locale = null)
{
    if (strpos($id, '.') === false and ($pos = strpos($id, '::')) !== false) {
        $package    = substr($id, 0, $pos);
        $id         = $package.'::main.'.substr($id, $pos + 2);
    }

    return app('translator')->trans($id, $parameters, $domain, $locale);
}


/*
|--------------------------------------------------------------------------
| Register The Composer Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so that we do not have to worry about the
| loading of any our classes "manually". Feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Include The Compiled Class File
|--------------------------------------------------------------------------
|
| To dramatically increase your application's performance, you may use a
| compiled class file which contains all of the classes commonly used
| by a request. The Artisan "optimize" is used to create this file.
|
*/

if (file_exists($compiled = __DIR__.'/compiled.php'))
{
	require $compiled;
}

/*
|--------------------------------------------------------------------------
| Setup Patchwork UTF-8 Handling
|--------------------------------------------------------------------------
|
| The Patchwork library provides solid handling of UTF-8 strings as well
| as provides replacements for all mb_* and iconv type functions that
| are not available by default in PHP. We'll setup this stuff here.
|
*/

Patchwork\Utf8\Bootup::initMbstring();

/*
|--------------------------------------------------------------------------
| Register The Laravel Auto Loader
|--------------------------------------------------------------------------
|
| We register an auto-loader "behind" the Composer loader that can load
| model classes on the fly, even if the autoload files have not been
| regenerated for the application. We'll add it to the stack here.
|
*/

Illuminate\Support\ClassLoader::register();

/*
|--------------------------------------------------------------------------
| Register The Workbench Loaders
|--------------------------------------------------------------------------
|
| The Laravel workbench provides a convenient place to develop packages
| when working locally. However we will need to load in the Composer
| auto-load files for the packages so that these can be used here.
|
*/

if (is_dir($workbench = __DIR__.'/../workbench'))
{
	Illuminate\Workbench\Starter::start($workbench);
}