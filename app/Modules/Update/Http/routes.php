<?php

ModuleRoute::context('Update');

ModuleRoute::get('admin/update', 'AdminUpdateController@index');
ModuleRoute::post('admin/update', 'AdminUpdateController@update');