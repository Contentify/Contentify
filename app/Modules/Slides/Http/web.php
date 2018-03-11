<?php

ModuleRoute::context('Slides');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/slidecats', 'AdminSlidecatsController');
    ModuleRoute::get(
        'admin/slidecats/{id}/restore',
        ['as' => 'slidecats.restore', 'uses' => 'AdminSlidecatsController@restore']
    );
    ModuleRoute::post('admin/slidecats/search', 'AdminSlidecatsController@search');

    ModuleRoute::resource('admin/slides', 'AdminSlidesController');
    ModuleRoute::get(
        'admin/slides/{id}/restore',
        ['as' => 'slides.restore', 'uses' => 'AdminSlidesController@restore']
    );
    ModuleRoute::post('admin/slides/search', 'AdminSlidesController@search');
});