<?php

ModuleRoute::context('Awards');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/awards', 'AdminAwardsController');
    ModuleRoute::get(
        'admin/awards/{id}/restore',
        ['as' => 'admin.awards.restore', 'uses' => 'AdminAwardsController@restore']
    );
    ModuleRoute::post('admin/awards/search', 'AdminAwardsController@search');
});

ModuleRoute::resource('awards', 'AwardsController', ['only' => ['index']]);
ModuleRoute::post('awards/search', 'AwardsController@search');