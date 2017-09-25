<?php

ModuleRoute::context('Contact');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/contact', 'AdminContactController', ['only' => ['index', 'show', 'destroy']]);
    ModuleRoute::get(
        'admin/contact/{id}/restore',
        ['as' => 'admin.contact.restore', 'uses' => 'AdminContactController@restore']
    );
});

ModuleRoute::get('contact', 'ContactController@index');
ModuleRoute::post('contact/store', 'ContactController@store');

ModuleRoute::get('join-us', 'JoinController@index');
ModuleRoute::post('join-us/store', 'JoinController@store');