<?php

ModuleRoute::context('Users');

ModuleRoute::get('users/{id}/password', 'UsersController@editPassword');
ModuleRoute::put('users/{id}/password', 'UsersController@updatePassword');
ModuleRoute::resource('users', 'UsersController', ['only' => ['index', 'show', 'edit', 'update']]);
ModuleRoute::get('users/{id}/{slug}', 'UsersController@show');
ModuleRoute::post('users/search', 'UsersController@search');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/users', 'AdminUsersController', ['only' => ['index', 'edit', 'update', 'destroy']]);
    ModuleRoute::post('admin/users/search', 'AdminUsersController@search');
    ModuleRoute::post('admin/users/{id}/{activate}', 'AdminUsersController@activate');

    ModuleRoute::get('admin/activities', 'AdminActivitiesController@index');
    ModuleRoute::get('admin/activities/delete/all', 'AdminActivitiesController@deleteAll');
    ModuleRoute::post('admin/activities/search', 'AdminActivitiesController@search');
});