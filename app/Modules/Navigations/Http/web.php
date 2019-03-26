<?php

ModuleRoute::context('Navigations');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/navigations', 'AdminNavigationsController');
    ModuleRoute::get(
        'admin/navigations/{id}/restore',
        ['as' => 'navigations.restore', 'uses' => 'AdminNavigationsController@restore']
    );
    ModuleRoute::post('admin/navigations/search', 'AdminNavigationsController@search');
});
