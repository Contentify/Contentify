<?php

ModuleRoute::context('Questions');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::resource('admin/question-cats', 'AdminQuestionCatsController');
    ModuleRoute::get(
        'admin/question-cats/{id}/restore',
        ['as' => 'question-cats.restore', 'uses' => 'AdminQuestionCatsController@restore']
    );
    ModuleRoute::post('admin/question-cats/search', 'AdminQuestionCatsController@search');

    ModuleRoute::resource('admin/questions', 'AdminQuestionsController');
    ModuleRoute::get(
        'admin/questions/{id}/restore',
        ['as' => 'questions.restore', 'uses' => 'AdminQuestionsController@restore']
    );
    ModuleRoute::post('admin/questions/search', 'AdminQuestionsController@search');
});

ModuleRoute::get('questions', 'QuestionsController@index');