<?php

ModuleRoute::context(__DIR__);

ModuleRoute::resource('admin/advertcats', 'AdminAdvertcatsController');
ModuleRoute::get(
    'admin/advertcats/{id}/restore', 
    ['as' => 'admin.advertcats.restore', 'uses' => 'AdminAdvertcatsController@restore']
);
ModuleRoute::post('admin/advertcats/search', 'AdminAdvertcatsController@search');

ModuleRoute::resource('admin/adverts', 'AdminAdvertsController');
ModuleRoute::get(
    'admin/adverts/{id}/restore', 
    ['as' => 'admin.adverts.restore', 'uses' => 'AdminAdvertsController@restore']
);
ModuleRoute::post('admin/adverts/search', 'AdminAdvertsController@search');

ModuleRoute::resource('adverts', 'AdvertsController', ['only' => ['show']]);
ModuleRoute::get('adverts/{id}/{slug}', 'AdvertsController@show');
