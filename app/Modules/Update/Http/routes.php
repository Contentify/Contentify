<?php

ModuleRoute::context('Update');

ModuleRoute::get('admin/update', 'AdminUpdateController@index');
ModuleRoute::post('admin/update', 'AdminUpdateController@update');
ModuleRoute::get('admin/update/status', 'AdminUpdateController@status');
ModuleRoute::get('admin/update/completed', 'AdminUpdateController@completed');