<?php

namespace App\Modules\Cups\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Cups\Providers\RouteServiceProvider');

        Lang::addNamespace('cups', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('cups', realpath(__DIR__.'/../Resources/Views'));
    }

}