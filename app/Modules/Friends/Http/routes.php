<?php

ModuleRoute::context('Friends');

Route::group(array('middleware' => 'auth'), function()
{
    ModuleRoute::get('friends/{id}', 'FriendsController@show');
    ModuleRoute::get('friends/add/{id}', 'FriendsController@add');
    ModuleRoute::get('friends/confirm/{id}', 'FriendsController@confirm');
    ModuleRoute::delete('friends/{id}', 'FriendsController@destroy');
});