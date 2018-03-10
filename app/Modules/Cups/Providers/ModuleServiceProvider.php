<?php

namespace App\Modules\Cups\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class ModuleServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Cups\Providers\RouteServiceProvider');

        Lang::addNamespace('cups', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('cups', realpath(__DIR__.'/../Resources/Views'));
    }

}