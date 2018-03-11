<?php

ModuleRoute::context('Streams');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/streams', 'AdminStreamsController');
    ModuleRoute::get(
        'admin/streams/{id}/restore',
        ['as' => 'streams.restore', 'uses' => 'AdminStreamsController@restore']
    );
    ModuleRoute::post('admin/streams/search', 'AdminStreamsController@search');
});

ModuleRoute::resource('streams', 'StreamsController', ['only' => ['index', 'show']]);
ModuleRoute::get('streams/{id}/{slug}', 'StreamsController@show');