<?php

ModuleRoute::context('News');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/news-cats', 'AdminNewsCatsController');
    ModuleRoute::get(
        'admin/news-cats/{id}/restore',
        ['as' => 'news-cats.restore', 'uses' => 'AdminNewsCatsController@restore']
    );
    ModuleRoute::post('admin/news-cats/search', 'AdminNewsCatsController@search');

    ModuleRoute::resource('admin/news', 'AdminNewsController');
    ModuleRoute::get(
        'admin/news/{id}/restore',
        ['as' => 'news.restore', 'uses' => 'AdminNewsController@restore']
    );
    ModuleRoute::post('admin/news/search', 'AdminNewsController@search');
});

ModuleRoute::get('news', 'NewsController@index');
ModuleRoute::get('news/{id}/{slug?}', ['as' => 'news.show', 'uses' => 'NewsController@show'])
    ->where('id', '[0-9]+');
ModuleRoute::get('news/{slug}', 'NewsController@showBySlug');
ModuleRoute::get('news/showStream/{timestamp?}', 'NewsController@showStream')->where('timestamp', '[0-9]+');
ModuleRoute::post('news/search', 'NewsController@search');