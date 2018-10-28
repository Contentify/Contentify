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