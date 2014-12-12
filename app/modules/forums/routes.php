<?php

ModuleRoute::context(__DIR__);

ModuleRoute::resource('admin/forums', 'AdminForumsController');
ModuleRoute::get(
    'admin/forums/{id}/restore', 
    ['as' => 'admin.forums.restore', 'uses' => 'AdminForumsController@restore']
);
ModuleRoute::post('admin/forums/search', 'AdminForumsController@search');

ModuleRoute::get('forums', 'ForumsController@index');
ModuleRoute::get('forums/{id}/{slug?}', 'ForumsController@show')->where('id', '[0-9]+');

ModuleRoute::get('forums/threads/{id}/{slug?}', 'ForumThreadsController@show')->where('id', '[0-9]+');
Route::group(array('before' => 'auth'), function()
{
    ModuleRoute::get('forums/threads/create/{id}', 'ForumThreadsController@create');
    ModuleRoute::post('forums/threads/{id}', 'ForumThreadsController@store');
    ModuleRoute::get('forums/threads/edit/{id}', 'ForumThreadsController@edit');
    ModuleRoute::put('forums/threads/{id}', 'ForumThreadsController@update');
    ModuleRoute::get('forums/threads/sticky/{id}', 'ForumThreadsController@sticky');
    ModuleRoute::get('forums/threads/closed/{id}', 'ForumThreadsController@closed');
    ModuleRoute::get('forums/threads/move/{id}', 'ForumThreadsController@getMove');
    ModuleRoute::post('forums/threads/move/{id}', 'ForumThreadsController@postMove');
    ModuleRoute::get('forums/threads/delete/{id}', 'ForumThreadsController@delete');
});
ModuleRoute::post('forums/search', 'ForumThreadsController@search');

ModuleRoute::get('forums/posts/perma/{id}/{slug?}', 'ForumPostsController@show');
ModuleRoute::get('forums/posts/delete/{id}', 'ForumPostsController@delete');
ModuleRoute::post('forums/posts/{id}', 'ForumPostsController@store');
ModuleRoute::get('forums/posts/edit/{id}', 'ForumPostsController@edit');
ModuleRoute::put('forums/posts/{id}', 'ForumPostsController@update');