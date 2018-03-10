<?php

namespace App\Modules\Diag\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class ModuleServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Diag\Providers\RouteServiceProvider');

        Lang::addNamespace('diag', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('diag', realpath(__DIR__.'/../Resources/Views'));
    }

}