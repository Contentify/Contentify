<?php

namespace App\Modules\Diag\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Diag\Providers\RouteServiceProvider');

        Lang::addNamespace('diag', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('diag', realpath(__DIR__.'/../Resources/Views'));
    }

}