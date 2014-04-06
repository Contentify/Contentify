<?php

ModuleRoute::context(__DIR__);

ModuleRoute::resource('admin/teams', 'AdminTeamsController');
ModuleRoute::get(
    'admin/teams/{id}/restore', 
    ['as' => 'admin.teams.restore', 'uses' => 'AdminTeamsController@restore']
);
ModuleRoute::post('admin/teams/search', 'AdminTeamsController@search');

ModuleRoute::resource('teams', 'TeamsController', ['only' => ['index', 'show']]);
ModuleRoute::get('teams/{id}/{slug}', 'TeamsController@show');

ModuleRoute::controller('admin/members', 'AdminMembersController');
ModuleRoute::post('admin/members/search', 'AdminMembersController@search');
