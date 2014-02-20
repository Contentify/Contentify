<?php

ModuleRoute::context(__DIR__);

//ModuleRoute::model('Game');

ModuleRoute::resource('users', 'UsersController', ['only' => ['index', 'show', 'update']]);
ModuleRoute::post('users/search', 'UsersController@search');
