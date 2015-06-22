<?php

ModuleRoute::context('Auth');

ModuleRoute::controller('auth/registration', 'RegistrationController');

ModuleRoute::controller('auth/login', 'LoginController', ['getIndex' => 'login']);

ModuleRoute::controller('auth/logout', 'LogoutController', ['getIndex' => 'logout']);

ModuleRoute::controller('auth/restore', 'RestorePasswordController');