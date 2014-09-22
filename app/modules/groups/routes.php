<?php

ModuleRoute::context(__DIR__);

ModuleRoute::resource('admin/groups', 'AdminGroupsController');
ModuleRoute::post('admin/groups/search', 'AdminGroupsController@search');
