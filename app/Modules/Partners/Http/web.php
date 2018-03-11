<?php

ModuleRoute::context('Partners');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/partnercats', 'AdminPartnercatsController');
    ModuleRoute::get(
        'admin/partnercats/{id}/restore',
        ['as' => 'partnercats.restore', 'uses' => 'AdminPartnercatsController@restore']
    );
    ModuleRoute::post('admin/partnercats/search', 'AdminPartnercatsController@search');

    ModuleRoute::resource('admin/partners', 'AdminPartnersController');
    ModuleRoute::get(
        'admin/partners/{id}/restore',
        ['as' => 'partners.restore', 'uses' => 'AdminPartnersController@restore']
    );
    ModuleRoute::post('admin/partners/search', 'AdminPartnersController@search');
});

ModuleRoute::resource('partners', 'PartnersController', ['only' => ['index']]);
ModuleRoute::get('partners/url/{id}/{slug?}', 'PartnersController@url');