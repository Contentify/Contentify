<?php

ModuleRoute::context(__DIR__);

ModuleRoute::resource('admin/maps', 'AdminMapsController');
ModuleRoute::get(
    'admin/maps/{id}/restore', 
    ['as' => 'admin.maps.restore', 'uses' => 'AdminMapsController@restore']
);
ModuleRoute::post('admin/maps/search', 'AdminMapsController@search');