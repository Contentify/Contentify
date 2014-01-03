<?php

ModuleRoute::context(__DIR__);

ModuleRoute::resource('admin/newscats', 'AdminNewscatsController');
ModuleRoute::post('admin/newscats/search', 'AdminNewscatsController@search');

ModuleRoute::resource('admin/news', 'AdminNewsController');
ModuleRoute::post('admin/news/search', 'AdminNewsController@search');

ModuleRoute::resource('news', 'NewsController', ['only' => ['index', 'show']]);

