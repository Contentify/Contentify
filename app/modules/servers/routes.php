<?php

ModuleRoute::context(__DIR__);

ModuleRoute::resource('admin/servers', 'AdminServersController');
ModuleRoute::get(
    'admin/servers/{id}/restore', 
    ['as' => 'admin.servers.restore', 'uses' => 'AdminServersController@restore']
);

ModuleRoute::get('servers', 'ServersController@index');