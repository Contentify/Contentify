<?php

ModuleRoute::context('Servers');

ModuleRoute::resource('admin/servers', 'AdminServersController');
ModuleRoute::get(
    'admin/servers/{id}/restore', 
    ['as' => 'admin.servers.restore', 'uses' => 'AdminServersController@restore']
);

ModuleRoute::get('servers', 'ServersController@index');