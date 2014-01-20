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
 * Frontend dashboard.
 */ 
Route::get('/', ['as' => 'home', 'uses' => 'App\Modules\News\Controllers\NewsController@showOverview']);

/*
 * Backend dashboard.
 * We prefer to use a route here instead of inside the modules' own routing file.
 * So there can't exist multiple modules that try to declare themselves as dashboard.
 * (Well, ofcourse they may try to... since routing is global. But they should not.)
 */ 
Route::get('admin', ['as' => 'admin.dashboard', 'before' => 'admin', 'uses' => 'App\Modules\Dashboard\Controllers\AdminDashboardController@getindex']);

/*
 * Comment component
 */
Route::post('comments/store', ['as' => 'comments.store', 'uses' => function()
{
    $foreignType = Input::get('foreigntype');
    $foreignId = Input::get('foreignid');
    return Comments::store($foreignType, $foreignId);
}]);

Route::get('comments/{id}', function($id)
{
    return Comments::get($id);
});

Route::get('comments/{id}/edit', ['as' => 'comments.edit', 'uses' => function($id)
{
    return Comments::edit($id);
}]);

Route::put('comments/{id}/update', ['as' => 'comments.update', 'uses' => function($id)
{
    return Comments::update($id);
}]);

Route::delete('comments/{id}/delete', ['as' => 'comments.delete', 'uses' => function($id)
{
    return Comments::delete($id);
}]);

/*
 * Captcha
 */
Route::get('captcha', ['as' => 'captcha', 'uses' => function()
{
    Captcha::make();
    $response = Response::make('', 200);

    $response->header('Content-Type', 'image/jpg');

    return $response;
}]);


/*
 * Installation
 */
Route::get('install', 'InstallController@index');

/*
 * Testing
 */
Route::get('test', function()
{
    //if (user()) dd(user()->hasAccess('test', 0));
    //Config::store("unicorns", "are epic");
    //dd(user()->hasAccess('backend'));
    //echo(FormGenerator::generate('news'));
});