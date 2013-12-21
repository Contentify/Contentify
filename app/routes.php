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

// Homepage:
Route::get('/', function()
{
	return View::make('frontend');
});

// Installation:
Route::get('/install', 'InstallController@index');

Route::get('/test', function()
{
	echo link_to_action('admin.games.destroy', 'Link', ['games' => 17, 'method' => 'delete']);
});