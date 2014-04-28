<?php

ModuleRoute::context(__DIR__);

ModuleRoute::resource('admin/pages', 'AdminPagesController');
ModuleRoute::get(
    'admin/pages/{id}/restore', 
    ['as' => 'admin.pages.restore', 'uses' => 'AdminPagesController@restore']
);
ModuleRoute::post('admin/pages/search', 'AdminPagesController@search');

ModuleRoute::get('pages/{id}/{slug?}', ['as' => 'pages.show', 'uses' => 'CustomPagesController@show'])
    ->where('id', '[0-9]+');
ModuleRoute::get('pages/{slug}', ['as' => 'pages.showSlug', 'uses' => 'CustomPagesController@showBySlug']);

ModuleRoute::get('articles', 'ArticlesController@index');
ModuleRoute::get('articles/{id}', ['as' => 'articles.show', 'uses' => 'ArticlesController@show']);
ModuleRoute::get('articles/{id}/{slug}', 'ArticlesController@show');