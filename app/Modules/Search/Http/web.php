<?php

ModuleRoute::context('Search');

ModuleRoute::get('search', 'SearchController@getIndex');
ModuleRoute::post('search/create', 'SearchController@postCreate');