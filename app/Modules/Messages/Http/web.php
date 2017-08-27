<?php

ModuleRoute::context('Messages');

ModuleRoute::group(array('middleware' => 'auth'), function()
{
    ModuleRoute::get('messages/{id}/{slug?}', 'MessagesController@show')->where('id', '[0-9]+');
    ModuleRoute::get('messages/create/{username?}', 'MessagesController@create');
    ModuleRoute::get('messages/reply/{id}/{slug?}', 'MessagesController@reply')->where('id', '[0-9]+');
    ModuleRoute::post('messages', 'MessagesController@store');
    ModuleRoute::delete('messages/{id}', 'MessagesController@destroy');

    ModuleRoute::resource('messages/inbox', 'InboxController', ['only' => ['index']]);
    ModuleRoute::post('messages/inbox/search', 'InboxController@search');

    ModuleRoute::resource('messages/outbox', 'OutboxController', ['only' => ['index']]);
    ModuleRoute::post('messages/outbox/search', 'OutboxController@search');
});