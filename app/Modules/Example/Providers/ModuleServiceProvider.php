<?php

namespace App\Modules\Example\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Example\Providers\RouteServiceProvider');

        Lang::addNamespace('example', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('example', realpath(__DIR__.'/../Resources/Views'));
    }

}