<?php

namespace App\Modules\Forums\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class ModuleServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Forums\Providers\RouteServiceProvider');

        Lang::addNamespace('forums', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('forums', realpath(__DIR__.'/../Resources/Views'));
    }

}