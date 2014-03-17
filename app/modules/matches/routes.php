<?php

ModuleRoute::context(__DIR__);

ModuleRoute::resource('admin/matches', 'AdminMatchesController');
ModuleRoute::get(
    'admin/matches/{id}/restore', 
    ['as' => 'admin.matches.restore', 'uses' => 'AdminMatchesController@restore']
);
ModuleRoute::post('admin/matches/search', 'AdminMatchesController@search');
