<?php namespace App\Modules\Countries\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class CountriesServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Countries\Providers\RouteServiceProvider');

        Lang::addNamespace('countries', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('countries', realpath(__DIR__.'/../Resources/Views'));
    }

}