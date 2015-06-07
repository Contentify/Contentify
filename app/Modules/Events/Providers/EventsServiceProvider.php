<?php namespace App\Modules\Events\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class EventsServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Events\Providers\RouteServiceProvider');

        Lang::addNamespace('events', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('events', realpath(__DIR__.'/../Resources/Views'));
    }

}