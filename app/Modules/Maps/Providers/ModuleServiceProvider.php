<?php namespace App\Modules\Maps\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class ModuleServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Maps\Providers\RouteServiceProvider');

        Lang::addNamespace('maps', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('maps', realpath(__DIR__.'/../Resources/Views'));
    }

}