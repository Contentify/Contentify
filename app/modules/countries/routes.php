<?php

ModuleRoute::context(__DIR__);

ModuleRoute::resource('admin/countries', 'AdminCountriesController');
ModuleRoute::post('admin/countries/search', 'AdminCountriesController@search');
