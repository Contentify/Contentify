<?php

ModuleRoute::context('Games');

ModuleRoute::model('Game');

ModuleRoute::resource('admin/games', 'GamesController');
//ModuleRoute::controller('admin/games', 'GamesController');
