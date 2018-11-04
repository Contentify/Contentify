<?php

ModuleRoute::context('Polls');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/polls', 'AdminPollsController');
    ModuleRoute::get(
        'admin/polls/{id}/restore',
        ['as' => 'polls.restore', 'uses' => 'AdminPollsController@restore']
    );
    ModuleRoute::post('admin/polls/search', 'AdminPollsController@search');
});

ModuleRoute::get('polls', 'PollsController@index');
ModuleRoute::get('polls/{id}/{slug?}', 'PollsController@show')->where('id', '[0-9]+');
ModuleRoute::post('polls/{id}/{slug?}', 'PollsController@vote')->where('id', '[0-9]+');
ModuleRoute::post('polls/search', 'PollsController@search');