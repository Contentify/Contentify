<?php

namespace App\Modules\Languages\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Languages\Providers\RouteServiceProvider');

        Lang::addNamespace('languages', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('languages', realpath(__DIR__.'/../Resources/Views'));
    }

}