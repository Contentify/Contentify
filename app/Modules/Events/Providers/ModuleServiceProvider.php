<?php

namespace App\Modules\Events\Providers;

use App;
use App\Modules\Events\Event;
use Illuminate\Support\Facades\Event as LaravelEvent;
use Illuminate\Support\ServiceProvider;
use Lang;
use User;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Events\Providers\RouteServiceProvider');

        Lang::addNamespace('events', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('events', realpath(__DIR__.'/../Resources/Views'));

        LaravelEvent::listen(Event::EVENT_NAME_REQUEST_EVENT_CREATION, function ($data) {
            $event = new Event($data);
            $event->creator_id = user() ? user()->id : User::DAEMON_USER_ID;
            $event->createSlug();
            $event->save();
        });
    }
}
