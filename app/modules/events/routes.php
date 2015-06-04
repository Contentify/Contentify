<?php

ModuleRoute::context(__DIR__);

ModuleRoute::resource('admin/events', 'AdminEventsController');
ModuleRoute::get(
    'admin/events/{id}/restore', 
    ['as' => 'admin.events.restore', 'uses' => 'AdminEventsController@restore']
);
ModuleRoute::post('admin/events/search', 'AdminEventsController@search');

ModuleRoute::resource('events', 'EventsController', ['only' => ['index', 'show']]);
ModuleRoute::get('events/{id}/{slug}', 'EventsController@show');