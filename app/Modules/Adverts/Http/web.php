<?php

ModuleRoute::context('Adverts');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/advert-cats', 'AdminAdvertCatsController');
    ModuleRoute::get(
        'admin/advert-cats/{id}/restore',
        ['as' => 'advert-cats.restore', 'uses' => 'AdminAdvertCatsController@restore']
    );
    ModuleRoute::post('admin/advert-cats/search', 'AdminAdvertCatsController@search');

    ModuleRoute::resource('admin/adverts', 'AdminAdvertsController');
    ModuleRoute::get(
        'admin/adverts/{id}/restore',
        ['as' => 'adverts.restore', 'uses' => 'AdminAdvertsController@restore']
    );
    ModuleRoute::post('admin/adverts/search', 'AdminAdvertsController@search');
});

ModuleRoute::get('adverts/url/{id}', 'AdvertsController@url');