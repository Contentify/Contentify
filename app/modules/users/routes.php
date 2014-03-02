<?php

ModuleRoute::context(__DIR__);

ModuleRoute::resource('users', 'UsersController', ['only' => ['index', 'show', 'edit', 'update']]);
ModuleRoute::get('users/{id}/{slug}', 'UsersController@show');
ModuleRoute::post('users/search', 'UsersController@search');
