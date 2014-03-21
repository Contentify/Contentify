<?php

ModuleRoute::context(__DIR__);

ModuleRoute::resource('admin/modules', 'AdminModulesController');
ModuleRoute::post('admin/modules/search', 'AdminModulesController@search');
ModuleRoute::match(['GET', 'POST'], 'admin/modules/{name}/install/{step?}', 'AdminModulesController@install');
