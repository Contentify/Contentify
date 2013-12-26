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

// Admin filter:
Route::when('admin/*', 'admin');

// Homepages:
Route::get('/', ['as' => 'home', 'uses' => function()
{
	return View::make('frontend');
}]);

// We prefer to use a route here than inside the modules own routing file.
// So there can't exist multiple modules that try to declare themselves as dashboard.
Route::get('admin/', ['as' => 'admin.dashboard', 'uses' => 'App\Modules\Dashboard\Controllers\AdminDashboardController@getindex']);

// Installation:
Route::get('/install', 'InstallController@index');

Route::get('/test', function()
{
    echo(SmartFormGenerator::generate('news'));

    //echo SmartFormBuilder::compile('test');

	//echo link_to_action('admin.games.destroy', 'Link', ['games' => 17, 'method' => 'delete']);
});