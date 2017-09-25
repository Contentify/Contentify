<?php

ModuleRoute::context('Maps');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/maps', 'AdminMapsController');
    ModuleRoute::get(
        'admin/maps/{id}/restore',
        ['as' => 'admin.maps.restore', 'uses' => 'AdminMapsController@restore']
    );
    ModuleRoute::post('admin/maps/search', 'AdminMapsController@search');
});