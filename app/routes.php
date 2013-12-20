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
	return View::make('hello');
});

// Installation:
Route::get('/install', 'InstallController@index');

Route::get('/test', function()
{

});