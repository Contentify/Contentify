<?php

ModuleRoute::context('Partners');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/partner-cats', 'AdminPartnerCatsController');
    ModuleRoute::get(
        'admin/partner-cats/{id}/restore',
        ['as' => 'partner-cats.restore', 'uses' => 'AdminPartnerCatsController@restore']
    );
    ModuleRoute::post('admin/partner-cats/search', 'AdminPartnerCatsController@search');

    ModuleRoute::resource('admin/partners', 'AdminPartnersController');
    ModuleRoute::get(
        'admin/partners/{id}/restore',
        ['as' => 'partners.restore', 'uses' => 'AdminPartnersController@restore']
    );
    ModuleRoute::post('admin/partners/search', 'AdminPartnersController@search');
});

ModuleRoute::resource('partners', 'PartnersController', ['only' => ['index']]);
ModuleRoute::get('partners/url/{id}/{slug?}', 'PartnersController@url');