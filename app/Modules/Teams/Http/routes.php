<?php

ModuleRoute::context('Teams');

ModuleRoute::resource('admin/teams', 'AdminTeamsController');
ModuleRoute::get(
    'admin/teams/{id}/restore', 
    ['as' => 'admin.teams.restore', 'uses' => 'AdminTeamsController@restore']
);
ModuleRoute::get('admin/teams/{id}/lineup', 'AdminTeamsController@lineup');
ModuleRoute::post('admin/teams/search', 'AdminTeamsController@search');

ModuleRoute::resource('teams', 'TeamsController', ['only' => ['index', 'show']]);
ModuleRoute::get('teams/{id}/{slug}', 'TeamsController@show');

ModuleRoute::controller('admin/members', 'AdminMembersController');
ModuleRoute::post('admin/members/search', 'AdminMembersController@search');