<?php

ModuleRoute::context('Images');

ModuleRoute::resource('admin/images', 'AdminImagesController');
ModuleRoute::post('admin/images/search', 'AdminImagesController@search');

ModuleRoute::get('editor-images', 'EditorImagesController@index');
ModuleRoute::post('editor-images', 'EditorImagesController@search');