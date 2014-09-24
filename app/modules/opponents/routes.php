<?php

ModuleRoute::context(__DIR__);

ModuleRoute::resource('admin/opponents', 'AdminOpponentsController');
ModuleRoute::get(
    'admin/opponents/{id}/restore', 
    ['as' => 'admin.opponents.restore', 'uses' => 'AdminOpponentsController@restore']
);
ModuleRoute::post('admin/opponents/search', 'AdminOpponentsController@search');