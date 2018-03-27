<?php

namespace App\Modules\Images\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Images\Providers\RouteServiceProvider');

        Lang::addNamespace('images', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('images', realpath(__DIR__.'/../Resources/Views'));
    }

}