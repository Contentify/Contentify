<?php

ModuleRoute::context(__DIR__);

ModuleRoute::resource('admin/partners', 'AdminPartnersController');
ModuleRoute::get(
    'admin/partners/{id}/restore', 
    ['as' => 'admin.partners.restore', 'uses' => 'AdminPartnersController@restore']
);
ModuleRoute::post('admin/partners/search', 'AdminPartnersController@search');

ModuleRoute::resource('partners', 'PartnersController', ['only' => ['index', 'show']]);
ModuleRoute::get('partners/{id}/{slug}', 'PartnersController@show');
