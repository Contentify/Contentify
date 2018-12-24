<?php

ModuleRoute::context('Comments');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/comments', 'AdminCommentsController');
    ModuleRoute::post('admin/comments/search', 'AdminCommentsController@search');
});
