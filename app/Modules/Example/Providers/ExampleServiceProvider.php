<?php namespace App\Modules\Example\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class ExampleServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Example\Providers\RouteServiceProvider');

        Lang::addNamespace('example', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('example', realpath(__DIR__.'/../Resources/Views'));
    }

}