<?php

ModuleRoute::context('Roles');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/roles', 'AdminRolesController');
    ModuleRoute::post('admin/roles/search', 'AdminRolesController@search');
});
