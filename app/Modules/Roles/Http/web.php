<?php

ModuleRoute::context('Roles');

ModuleRoute::resource('admin/roles', 'AdminRolesController');
ModuleRoute::post('admin/roles/search', 'AdminRolesController@search');
