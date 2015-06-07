<?php

ModuleRoute::context('Groups');

ModuleRoute::resource('admin/groups', 'AdminGroupsController');
ModuleRoute::post('admin/groups/search', 'AdminGroupsController@search');
