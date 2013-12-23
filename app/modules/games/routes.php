<?php

ModuleRoute::context(__DIR__);

ModuleRoute::model('Game');

ModuleRoute::resource('admin/games', 'AdminGamesController');
ModuleRoute::post('admin/games/search', 'AdminGamesController@search');
