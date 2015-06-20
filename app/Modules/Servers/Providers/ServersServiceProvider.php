<?php namespace App\Modules\Servers\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class ServersServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Servers\Providers\RouteServiceProvider');

        Lang::addNamespace('servers', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('servers', realpath(__DIR__.'/../Resources/Views'));
    }

}