<?php namespace App\Modules\Visitors\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class ModuleServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Visitors\Providers\RouteServiceProvider');

        Lang::addNamespace('visitors', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('visitors', realpath(__DIR__.'/../Resources/Views'));
    }

}