<?php

ModuleRoute::context('Auth');

ModuleRoute::get('auth/registration/create', 'RegistrationController@getCreate');
ModuleRoute::post('auth/registration/create', 'RegistrationController@postCreate');

ModuleRoute::get('auth/steam', 'LoginController@getSteam');
ModuleRoute::post('auth/steam', 'LoginController@postSteam');
ModuleRoute::get('auth/login', ['as' => 'login', 'uses' => 'LoginController@getLogin']);
ModuleRoute::post('auth/login', 'LoginController@postLogin');

ModuleRoute::get('auth/logout',  ['as' => 'logout', 'uses' => 'LogoutController@getIndex']);

ModuleRoute::get('auth/restore', 'RestorePasswordController@getIndex');
ModuleRoute::post('auth/restore', 'RestorePasswordController@postIndex');
ModuleRoute::get('auth/restore/new/{email}/{code}', 'RestorePasswordController@getNew');

ModuleRoute::get('auth/username/check/{username}', 'RegistrationController@checkUsername');