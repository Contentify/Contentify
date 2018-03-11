<?php

ModuleRoute::context('Countries');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/countries', 'AdminCountriesController');
    ModuleRoute::get(
        'admin/countries/{id}/restore',
        ['as' => 'countries.restore', 'uses' => 'AdminCountriesController@restore']
    );
    ModuleRoute::post('admin/countries/search', 'AdminCountriesController@search');
});