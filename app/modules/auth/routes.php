<?php

ModuleRoute::context(__DIR__);

ModuleRoute::controller('auth/registration', 'RegistrationController');

ModuleRoute::controller('auth/login', 'LoginController', ['getIndex' => 'login']);

ModuleRoute::controller('auth/logout', 'LogoutController', ['getIndex' => 'logout']);