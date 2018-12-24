<?php

ModuleRoute::context('Pages');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/pages', 'AdminPagesController');
    ModuleRoute::get(
        'admin/pages/{id}/restore',
        ['as' => 'pages.restore', 'uses' => 'AdminPagesController@restore']
    );
    ModuleRoute::post('admin/pages/search', 'AdminPagesController@search');
});

ModuleRoute::get('pages/{id}/{slug?}', ['as' => 'pages.show', 'uses' => 'CustomPagesController@show'])
    ->where('id', '[0-9]+');
ModuleRoute::get('pages/{slug}', ['as' => 'pages.showSlug', 'uses' => 'CustomPagesController@showBySlug']);
ModuleRoute::get('impressum', ['as' => 'pages.showImpressum', 'uses' => 'CustomPagesController@showImpressum']);

ModuleRoute::get('articles', 'ArticlesController@index');
ModuleRoute::get('articles/{id}', ['as' => 'articles.show', 'uses' => 'ArticlesController@show']);
ModuleRoute::get('articles/{id}/{slug}', 'ArticlesController@show');

ModuleRoute::get('editor-templates/{id}', 'EditorTemplatesController@show');
ModuleRoute::get('editor-templates', 'EditorTemplatesController@index');

ModuleRoute::get('privacy-policy', 'PrivacyPolicyController@index');
