<?php namespace App\Modules\Messages\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class MessagesServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Messages\Providers\RouteServiceProvider');

        Lang::addNamespace('messages', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('messages', realpath(__DIR__.'/../Resources/Views'));
    }

}