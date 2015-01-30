<?php

ModuleRoute::context(__DIR__);

ModuleRoute::resource('admin/navigations', 'AdminNavigationsController');
ModuleRoute::get(
    'admin/navigations/{id}/restore', 
    ['as' => 'admin.navigations.restore', 'uses' => 'AdminNavigationsController@restore']
);
ModuleRoute::post('admin/navigations/search', 'AdminNavigationsController@search');