<?php

ModuleRoute::context('Config');

ModuleRoute::get('admin/config/log/clear', 'AdminConfigController@clearLog');
ModuleRoute::get('admin/config', 'AdminConfigController@getIndex');
ModuleRoute::get('admin/config/info', 'AdminConfigController@getInfo');
ModuleRoute::get('admin/config/log', 'AdminConfigController@getLog');
ModuleRoute::get('admin/config/plain-log', 'AdminConfigController@getPlainLog');
ModuleRoute::get('admin/config/optimize', 'AdminConfigController@getOptimize');
ModuleRoute::get('admin/config/export', 'AdminConfigController@getExport');
ModuleRoute::get('admin/config/compile-less', 'AdminConfigController@getCompileLess');
ModuleRoute::get('admin/config/clear-cache', 'AdminConfigController@getClearCache');
ModuleRoute::put('admin/config', ['as' => 'admin.config.update', 'uses' => 'AdminConfigController@update']);