<?php namespace App\Modules\Config\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class ModuleServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Config\Providers\RouteServiceProvider');

        Lang::addNamespace('config', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('config', realpath(__DIR__.'/../Resources/Views'));
    }

}