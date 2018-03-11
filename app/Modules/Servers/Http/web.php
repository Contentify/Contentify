<?php

ModuleRoute::context('Servers');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/servers', 'AdminServersController');
    ModuleRoute::get(
        'admin/servers/{id}/restore',
        ['as' => 'servers.restore', 'uses' => 'AdminServersController@restore']
    );
});

ModuleRoute::get('servers', 'ServersController@index');