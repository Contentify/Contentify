<?php

ModuleRoute::context('Teams');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/teams', 'AdminTeamsController');
    ModuleRoute::get(
        'admin/teams/{id}/restore',
        ['as' => 'teams.restore', 'uses' => 'AdminTeamsController@restore']
    );
    ModuleRoute::get('admin/teams/{id}/lineup', 'AdminTeamsController@lineup');
    ModuleRoute::post('admin/teams/search', 'AdminTeamsController@search');

    ModuleRoute::get('admin/members', 'AdminMembersController@getIndex');
    ModuleRoute::delete('admin/members/delete/{userId}/{teamId}', 'AdminMembersController@deleteDelete');
    ModuleRoute::get('admin/members/add/{id}', 'AdminMembersController@getAdd');
    ModuleRoute::post('admin/members/add/{userId}/{teamId}', 'AdminMembersController@postAdd');
    ModuleRoute::get('admin/members/edit/{userId}/{teamId}', 'AdminMembersController@getEdit');
    ModuleRoute::post('admin/members/update/{userId}/{teamId}', 'AdminMembersController@postUpdate');
    ModuleRoute::post('admin/members/search', 'AdminMembersController@postSearch');
});

ModuleRoute::resource('teams', 'TeamsController', ['only' => ['index', 'show']]);
ModuleRoute::get('teams/{id}/{slug}', 'TeamsController@show');