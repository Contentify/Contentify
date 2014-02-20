<?php

ModuleRoute::context(__DIR__);

ModuleRoute::controller('admin/config', 'AdminConfigController');
ModuleRoute::put('admin/config', ['as' => 'admin.config.update', 'uses' => 'AdminConfigController@update']);