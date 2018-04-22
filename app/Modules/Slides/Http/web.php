<?php

ModuleRoute::context('Slides');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/slide-cats', 'AdminSlideCatsController');
    ModuleRoute::get(
        'admin/slide-cats/{id}/restore',
        ['as' => 'slide-cats.restore', 'uses' => 'AdminSlideCatsController@restore']
    );
    ModuleRoute::post('admin/slide-cats/search', 'AdminSlideCatsController@search');

    ModuleRoute::resource('admin/slides', 'AdminSlidesController');
    ModuleRoute::get(
        'admin/slides/{id}/restore',
        ['as' => 'slides.restore', 'uses' => 'AdminSlidesController@restore']
    );
    ModuleRoute::post('admin/slides/search', 'AdminSlidesController@search');
});