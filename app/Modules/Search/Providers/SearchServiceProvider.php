<?php namespace App\Modules\Search\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class SearchServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Search\Providers\RouteServiceProvider');

        Lang::addNamespace('search', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('search', realpath(__DIR__.'/../Resources/Views'));
    }

}