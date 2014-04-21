<?php

ModuleRoute::context(__DIR__);

ModuleRoute::resource('admin/adverts', 'AdminAdvertsController');
ModuleRoute::get(
    'admin/adverts/{id}/restore', 
    ['as' => 'admin.adverts.restore', 'uses' => 'AdminAdvertsController@restore']
);
ModuleRoute::post('admin/adverts/search', 'AdminAdvertsController@search');

ModuleRoute::resource('adverts', 'AdvertsController', ['only' => ['show']]);
ModuleRoute::get('adverts/{id}/{slug}', 'AdvertsController@show');
