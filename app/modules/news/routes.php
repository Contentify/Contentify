<?php

ModuleRoute::context(__DIR__);

ModuleRoute::resource('admin/newscats', 'AdminNewscatsController');
ModuleRoute::get(
    'admin/newscats/{id}/restore', 
    ['as' => 'admin.newscats.restore', 'uses' => 'AdminNewscatsController@restore']
);
ModuleRoute::post('admin/newscats/search', 'AdminNewscatsController@search');

ModuleRoute::resource('admin/news', 'AdminNewsController');
ModuleRoute::get(
    'admin/news/{id}/restore', 
    ['as' => 'admin.news.restore', 'uses' => 'AdminNewsController@restore']
);
ModuleRoute::post('admin/news/search', 'AdminNewsController@search');

ModuleRoute::resource('news', 'NewsController', ['only' => ['index', 'show']]);
ModuleRoute::get('news/{id}/{slug}', 'NewsController@show');
ModuleRoute::post('news/search', 'NewsController@search');

