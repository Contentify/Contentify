<?php

ModuleRoute::context('Downloads');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/downloadcats', 'AdminDownloadcatsController');
    ModuleRoute::get(
        'admin/downloadcats/{id}/restore',
        ['as' => 'downloadcats.restore', 'uses' => 'AdminDownloadcatsController@restore']
    );
    ModuleRoute::post('admin/downloadcats/search', 'AdminDownloadcatsController@search');

    ModuleRoute::resource('admin/downloads', 'AdminDownloadsController');
    ModuleRoute::get(
        'admin/downloads/{id}/restore',
        ['as' => 'downloads.restore', 'uses' => 'AdminDownloadsController@restore']
    );
    ModuleRoute::post('admin/downloads/search', 'AdminDownloadsController@search');
});

ModuleRoute::resource('downloads', 'DownloadsController', ['only' => ['index', 'show']]);
ModuleRoute::get('downloads/category/{id}/{slug?}', 'DownloadsController@showCategory');
ModuleRoute::get('downloads/{id}/{slug?}', 'DownloadsController@show');
ModuleRoute::post('downloads/perform/{id}', 'DownloadsController@perform');
ModuleRoute::post('downloads/search', 'DownloadsController@search');