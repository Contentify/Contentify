<?php

ModuleRoute::context('Events');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/events', 'AdminEventsController');
    ModuleRoute::get(
        'admin/events/{id}/restore',
        ['as' => 'events.restore', 'uses' => 'AdminEventsController@restore']
    );
    ModuleRoute::post('admin/events/search', 'AdminEventsController@search');
});

ModuleRoute::resource('events', 'EventsController', ['only' => ['index', 'show']]);
ModuleRoute::get('events/{id}/{slug}', 'EventsController@show');
ModuleRoute::get('calendar/{year?}/{month?}', 'EventsController@calendar');