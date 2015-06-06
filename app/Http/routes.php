<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/* 
 * Admin filter:
 */ 
Route::when('admin/*', 'admin');

/*
 * Frontend dashboard.
 */ 
if (! installed()) {
    // TODO: This is just a dirty implementation only for beta testing!
    Route::get('/', ['as' => 'home', 'uses' => function() {
        //$view = View::make('maintenance')->render();
        //echo $view;
        return Redirect::to('/install');
    }]);
} else {
    Route::get('/', ['as' => 'home', 'uses' => 'App\Modules\News\Controllers\NewsController@showOverview']);
}

/*
 * Backend dashboard.
 * We prefer to use a route here instead of inside the modules' own routing file.
 * So there can't exist multiple modules that try to declare themselves as dashboard.
 * (Well, ofcourse they may try to... since routing is global. But they should not.)
 */ 
Route::get('admin', [
    'as' => 'admin.dashboard', 
    'before' => 'admin', 
    'uses' => 'App\Modules\Dashboard\Controllers\AdminDashboardController@getindex'
]);

/*
 * Comment component
 */
Route::get('comments/paginate/{foreignType}/{foreignId}', function($foreignType, $foreignId)
{
    return Comments::paginate($foreignType, $foreignId);
});

Route::post('comments/store', ['as' => 'comments.store', 'before' => 'csrf', 'uses' => function()
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

Route::put('comments/{id}/update', ['as' => 'comments.update', 'before' => 'csrf', 'uses' => function($id)
{
    return Comments::update($id);
}]);

Route::delete('comments/{id}/delete', ['as' => 'comments.delete', 'before' => 'csrf', 'uses' => function($id)
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
Route::post('install', 'InstallController@index');

/*
 * Execute Cron Jobs
 */
Route::get('jobs', function()
{
    Jobs::run();

    return Response::make('1', 200);
});

/*
 * Testing
 */
Route::get('test', function()
{ 
    /*
    $controller = new InstallController();
    $controller->create('table', function($table)
    {

    });
    */
   
    $test = \HTML::gravatar('test@test.com');
    dd($test);
});