<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/* 
 * Admin filter:
 */ 
Route::when('admin/*', 'admin');

/*
 * Homepages:
 */ 
Route::get('/', ['as' => 'home', 'uses' => function()
{
	return View::make('index');
}]);

/*
 * We prefer to use a route here instead of inside the modules' own routing file.
 * So there can't exist multiple modules that try to declare themselves as dashboard.
 * (Well, ofcourse they may try to... since routing is global. But they should not.)
 */ 
Route::get('admin', ['as' => 'admin.dashboard', 'before' => 'admin', 'uses' => 'App\Modules\Dashboard\Controllers\AdminDashboardController@getindex']);

/*
 * Installation
 */
Route::get('install', 'InstallController@index');

/*
 * Testing
 */
Route::get('test', function()
{
    /*
        Sentry::createGroup(array(
            'name'        => 'Testers',
            'permissions' => array('permission' => 1)
        ));
        */
    dd(user()->hasAccess('backend'));
    //echo(SmartFormGenerator::generate('news'));
});