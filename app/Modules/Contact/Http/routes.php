<?php

ModuleRoute::context('Contact');

ModuleRoute::resource('admin/contact', 'AdminContactController', ['only' => ['index', 'show', 'destroy']]);
ModuleRoute::get(
    'admin/contact/{id}/restore', 
    ['as' => 'admin.contact.restore', 'uses' => 'AdminContactController@restore']
);

ModuleRoute::controller('contact', 'ContactController');