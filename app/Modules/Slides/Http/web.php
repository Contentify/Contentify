<?php

ModuleRoute::context('Slides');

ModuleRoute::resource('admin/slidecats', 'AdminSlidecatsController');
ModuleRoute::get(
    'admin/slidecats/{id}/restore', 
    ['as' => 'admin.slidecats.restore', 'uses' => 'AdminSlidecatsController@restore']
);
ModuleRoute::post('admin/slidecats/search', 'AdminSlidecatsController@search');

ModuleRoute::resource('admin/slides', 'AdminSlidesController');
ModuleRoute::get(
    'admin/slides/{id}/restore', 
    ['as' => 'admin.slides.restore', 'uses' => 'AdminSlidesController@restore']
);
ModuleRoute::post('admin/slides/search', 'AdminSlidesController@search');
