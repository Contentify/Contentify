<?php

namespace App\Modules\Auth\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Auth\Providers\RouteServiceProvider');

        Lang::addNamespace('auth', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('auth', realpath(__DIR__.'/../Resources/Views'));
    }

}