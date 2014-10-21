<?php

ModuleRoute::context(__DIR__);

ModuleRoute::controller('auth/registration', 'RegistrationController');

ModuleRoute::controller('auth/login', 'LoginController', ['getIndex' => 'login']);

ModuleRoute::controller('auth/logout', 'LogoutController', ['getIndex' => 'logout']);

ModuleRoute::controller('auth/restore', 'RestorePasswordController');

ModuleRoute::get('admin/auth/config', 'AdminConfigController@edit');
ModuleRoute::put('admin/auth/config', 'AdminConfigController@update');