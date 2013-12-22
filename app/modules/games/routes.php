<?php

ModuleRoute::context(__DIR__);

ModuleRoute::model('Game');

ModuleRoute::resource('admin/games', 'GamesController');
//ModuleRoute::controller('admin/games', 'GamesController');
