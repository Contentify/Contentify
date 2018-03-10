<?php

namespace App\Modules\Dashboard\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class ModuleServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Dashboard\Providers\RouteServiceProvider');

        Lang::addNamespace('dashboard', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('dashboard', realpath(__DIR__.'/../Resources/Views'));
    }

}