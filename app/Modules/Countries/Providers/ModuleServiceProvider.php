<?php

namespace App\Modules\Countries\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Countries\Providers\RouteServiceProvider');

        Lang::addNamespace('countries', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('countries', realpath(__DIR__.'/../Resources/Views'));
    }

}