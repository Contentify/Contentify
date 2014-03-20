<?php

ModuleRoute::context(__DIR__);

ModuleRoute::resource('admin/modules', 'AdminModulesController');
ModuleRoute::post('admin/modules/search', 'AdminModulesController@search');
