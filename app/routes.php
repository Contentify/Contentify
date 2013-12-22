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
Route::get('/', function()
{
	return View::make('frontend');
});

Route::get('admin/', 'App\Modules\Dashboard\Controllers\AdminDashboardController@getindex');

// Installation:
Route::get('/install', 'InstallController@index');

Route::get('/test', function()
{
    //$game = App\Modules\Games\Models\Game::find(19);

    //echo(AutoFormBuilder::generate($game));

	//echo link_to_action('admin.games.destroy', 'Link', ['games' => 17, 'method' => 'delete']);
});