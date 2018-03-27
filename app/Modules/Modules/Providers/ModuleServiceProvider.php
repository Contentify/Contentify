<?php

namespace App\Modules\Modules\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Modules\Providers\RouteServiceProvider');

        Lang::addNamespace('modules', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('modules', realpath(__DIR__.'/../Resources/Views'));
    }

}