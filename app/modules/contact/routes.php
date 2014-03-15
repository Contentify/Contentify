<?php

ModuleRoute::context(__DIR__);

ModuleRoute::controller('contact', 'ContactController');
ModuleRoute::resource('admin/contact', 'AdminContactController', ['only' => ['index', 'show', 'destroy']]);
ModuleRoute::get(
    'admin/contact/{id}/restore', 
    ['as' => 'admin.contact.restore', 'uses' => 'AdminContactController@restore']
);