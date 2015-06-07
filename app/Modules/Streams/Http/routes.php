<?php

ModuleRoute::context('Streams');

ModuleRoute::resource('admin/streams', 'AdminStreamsController');
ModuleRoute::get(
    'admin/streams/{id}/restore', 
    ['as' => 'admin.streams.restore', 'uses' => 'AdminStreamsController@restore']
);
ModuleRoute::post('admin/streams/search', 'AdminStreamsController@search');

ModuleRoute::resource('streams', 'StreamsController', ['only' => ['index', 'show']]);
ModuleRoute::get('streams/{id}/{slug}', 'StreamsController@show');