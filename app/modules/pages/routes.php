<?php

ModuleRoute::context(__DIR__);

ModuleRoute::resource('admin/pages', 'AdminPagesController');
ModuleRoute::get(
    'admin/pages/{id}/restore', 
    ['as' => 'admin.pages.restore', 'uses' => 'AdminPagesController@restore']
);
ModuleRoute::post('admin/pages/search', 'AdminPagesController@search');

ModuleRoute::resource('pages', 'CustomPagesController', ['only' => ['show']]);
ModuleRoute::get('pages/{id}/{slug}', 'CustomPagesController@show');

ModuleRoute::resource('articles', 'ArticlesController', ['only' => ['index', 'show']]);
ModuleRoute::get('articles/{id}/{slug}', 'ArticlesController@show');