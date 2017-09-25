<?php

ModuleRoute::context('Modules');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/modules', 'AdminModulesController');
    ModuleRoute::post('admin/modules/search', 'AdminModulesController@search');
    ModuleRoute::match(['GET', 'POST'], 'admin/modules/{name}/install/{step?}',
        ['as' => 'modules.install', 'uses' => 'AdminModulesController@install']
    )->where('step', '[0-9]+');
});