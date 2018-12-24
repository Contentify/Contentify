<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
 * Frontend dashboard.
 */ 
if (! installed()) {
    Route::get('/', ['as' => 'home', 'uses' => function() {
        return Redirect::to('/install.php');
    }]);
} else {
    Route::get('/', ['as' => 'home', 'uses' => 'App\Modules\News\Http\Controllers\NewsController@showStream']);
}

/*
 * Backend dashboard.
 * We prefer to use a route here instead of inside the modules' own routing file.
 * So there can't exist multiple modules that try to declare themselves as dashboard.
 * (Well, of course they may try to... since routing is global. But they should not.)
 */ 
Route::get('admin', [
    'as' => 'admin.dashboard', 
    'before' => 'admin', 
    'uses' => 'App\Modules\Dashboard\Http\Controllers\AdminDashboardController@getindex'
]);

/*
 * Comment component
 */
Route::get('comments/paginate/{foreignType}/{foreignId}', function($foreignType, $foreignId)
{
    return Comments::paginate($foreignType, $foreignId);
});

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
 * Ratings
 */
Route::post('ratings/store', ['as' => 'ratings.store', 'uses' => function()
{
    $foreignType = Input::get('foreigntype');
    $foreignId = Input::get('foreignid');

    return Ratings::store($foreignType, $foreignId);
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
Route::get('dev', function()
{
    $controller = new InstallController();
    #$controller->create('questions', function($table)
    #{
    #    $table->text('answer')->nullable();
    #    $table->boolean('published')->default(false);
    #}, [], ['slug']);
});
