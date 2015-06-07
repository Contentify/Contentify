<?php namespace App\Modules\Awards\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class AwardsServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Awards\Providers\RouteServiceProvider');

        Lang::addNamespace('awards', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('awards', realpath(__DIR__.'/../Resources/Views'));
    }

}