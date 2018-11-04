<?php

ModuleRoute::context('Contact');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/contact', 'AdminContactController', ['only' => ['index', 'show', 'destroy']]);
    ModuleRoute::get(
        'admin/contact/{id}/restore',
        ['as' => 'contact.restore', 'uses' => 'AdminContactController@restore']
    );
    ModuleRoute::post('admin/contact/{id}/', 'AdminContactController@reply');
});

ModuleRoute::get('contact', 'ContactController@index');
ModuleRoute::post('contact/store', 'ContactController@store');

ModuleRoute::get('application', 'ApplicationController@index');
ModuleRoute::post('application/store', 'ApplicationController@store');