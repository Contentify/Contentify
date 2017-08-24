<?php

ModuleRoute::context('Config');


ModuleRoute::get('admin/config/log/clear', 'AdminConfigController@clearLog');
ModuleRoute::get('admin/config', 'getIndex');
ModuleRoute::get('admin/config/info', 'getInfo');
ModuleRoute::get('admin/config/optimize', 'getOptimize');
ModuleRoute::get('admin/config/export', 'getExport');
ModuleRoute::get('admin/config/log', 'getLog');
ModuleRoute::put('admin/config', ['as' => 'admin.config.update', 'uses' => 'AdminConfigController@update']);