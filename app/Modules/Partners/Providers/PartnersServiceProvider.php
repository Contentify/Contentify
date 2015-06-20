<?php namespace App\Modules\Partners\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class PartnersServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Partners\Providers\RouteServiceProvider');

        Lang::addNamespace('partners', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('partners', realpath(__DIR__.'/../Resources/Views'));
    }

}