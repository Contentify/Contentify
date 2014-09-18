<?php

ModuleRoute::context(__DIR__);

ModuleRoute::resource('admin/galleries', 'AdminGalleriesController');
ModuleRoute::get(
    'admin/galleries/{id}/restore', 
    ['as' => 'admin.galleries.restore', 'uses' => 'AdminGalleriesController@restore']
);
ModuleRoute::post('admin/galleries/search', 'AdminGalleriesController@search');