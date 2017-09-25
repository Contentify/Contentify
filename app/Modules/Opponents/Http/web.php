<?php

ModuleRoute::context('Opponents');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/opponents', 'AdminOpponentsController');
    ModuleRoute::get(
        'admin/opponents/{id}/restore',
        ['as' => 'admin.opponents.restore', 'uses' => 'AdminOpponentsController@restore']
    );
    ModuleRoute::get('admin/opponents/{id}/lineup', 'AdminOpponentsController@lineup');
    ModuleRoute::post('admin/opponents/search', 'AdminOpponentsController@search');
});