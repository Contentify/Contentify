<?php

namespace App\Modules\Games\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class ModuleServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Games\Providers\RouteServiceProvider');

        Lang::addNamespace('games', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('games', realpath(__DIR__.'/../Resources/Views'));
    }

}