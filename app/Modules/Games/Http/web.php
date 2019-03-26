<?php

ModuleRoute::context('Games');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/games', 'AdminGamesController');
    ModuleRoute::get(
        'admin/games/{id}/restore',
        ['as' => 'games.restore', 'uses' => 'AdminGamesController@restore']
    );
    ModuleRoute::post('admin/games/search', 'AdminGamesController@search');
});
