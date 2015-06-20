<?php namespace App\Modules\Streams\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class StreamsServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Streams\Providers\RouteServiceProvider');

        Lang::addNamespace('streams', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('streams', realpath(__DIR__.'/../Resources/Views'));
    }

}