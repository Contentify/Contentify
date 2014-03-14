<?php

ModuleRoute::context(__DIR__);

//ModuleRoute::model('Image');

ModuleRoute::resource('admin/images', 'AdminImagesController');
ModuleRoute::post('admin/images/search', 'AdminImagesController@search');