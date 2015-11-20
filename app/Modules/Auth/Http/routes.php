<?php

ModuleRoute::context('Auth');

ModuleRoute::controller('auth/registration', 'RegistrationController');

ModuleRoute::get('auth/steam', 'LoginController@getSteam');
ModuleRoute::get('auth/login', ['as' => 'login', 'uses' => 'LoginController@getLogin']);
ModuleRoute::post('auth/login', 'LoginController@postLogin');

ModuleRoute::controller('auth/logout', 'LogoutController', ['getIndex' => 'logout']);

ModuleRoute::controller('auth/restore', 'RestorePasswordController');

ModuleRoute::get('auth/username/check/{username}', 'RegistrationController@checkUsername');