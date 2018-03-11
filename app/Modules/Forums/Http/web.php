<?php

ModuleRoute::context('Forums');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::get('admin/forums/config', 'AdminConfigController@edit');
    ModuleRoute::put('admin/forums/config', 'AdminConfigController@update');

    ModuleRoute::resource('admin/forums', 'AdminForumsController');
    ModuleRoute::get(
        'admin/forums/{id}/restore',
        ['as' => 'forums.restore', 'uses' => 'AdminForumsController@restore']
    );
    ModuleRoute::post('admin/forums/search', 'AdminForumsController@search');

    ModuleRoute::get('admin/forum-reports',
        ['as' => 'reports.index', 'uses' => 'AdminReportsController@index']);
    ModuleRoute::delete('admin/forum-reports/{id}',
        ['as' => 'reports.destroy', 'uses' => 'AdminReportsController@destroy']);
});

ModuleRoute::get('forums', 'ForumsController@index');
ModuleRoute::get('forums/{id}/{slug?}', 'ForumsController@show')->where('id', '[0-9]+');

ModuleRoute::get('forums/threads/{id}/{slug?}', 'ThreadsController@show')->where('id', '[0-9]+');
ModuleRoute::get('forums/threads/new', 'ThreadsController@showNew');
ModuleRoute::group(array('middleware' => 'auth'), function()
{
    ModuleRoute::get('forums/threads/create/{id}', 'ThreadsController@create');
    ModuleRoute::post('forums/threads/{id}', 'ThreadsController@store');
    ModuleRoute::get('forums/threads/edit/{id}', 'ThreadsController@edit');
    ModuleRoute::put('forums/threads/{id}', 'ThreadsController@update');
    ModuleRoute::get('forums/threads/sticky/{id}', 'ThreadsController@sticky');
    ModuleRoute::get('forums/threads/closed/{id}', 'ThreadsController@closed');
    ModuleRoute::get('forums/threads/move/{id}', 'ThreadsController@getMove');
    ModuleRoute::post('forums/threads/move/{id}', 'ThreadsController@postMove');
    ModuleRoute::get('forums/threads/delete/{id}', 'ThreadsController@delete');
});
ModuleRoute::post('forums/search', 'ThreadsController@search');

ModuleRoute::get('forums/posts/perma/{id}/{slug?}', 'PostsController@show');
ModuleRoute::get('forums/posts/user/{id}/{slug?}', 'PostsController@showUserPosts');
ModuleRoute::group(array('middleware' => 'auth'), function()
{
    ModuleRoute::get('forums/posts/{id}', 'PostsController@get');
    ModuleRoute::get('forums/posts/delete/{id}', 'PostsController@delete');
    ModuleRoute::post('forums/posts/{id}', 'PostsController@store');
    ModuleRoute::get('forums/posts/edit/{id}', 'PostsController@edit');
    ModuleRoute::get('forums/posts/report/{id}', 'PostsController@report');
    ModuleRoute::put('forums/posts/{id}', 'PostsController@update');
});