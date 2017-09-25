<?php

ModuleRoute::context('Matches');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/matches', 'AdminMatchesController');
    ModuleRoute::get(
        'admin/matches/{id}/restore',
        ['as' => 'admin.matches.restore', 'uses' => 'AdminMatchesController@restore']
    );
    ModuleRoute::post('admin/matches/search', 'AdminMatchesController@search');

    ModuleRoute::post('admin/matches/scores/store', 'AdminMatchScoresController@store');
    ModuleRoute::delete('admin/matches/scores/{id}', 'AdminMatchScoresController@destroy');
    ModuleRoute::put('admin/matches/scores/{id}', 'AdminMatchScoresController@update');
});

ModuleRoute::get('matches', ['as' => 'matches.index', 'uses' => 'MatchesController@index']);
ModuleRoute::get('matches/{id}', ['as' => 'matches.show', 'uses' => 'MatchesController@show']);
ModuleRoute::post('matches/search', 'MatchesController@search');