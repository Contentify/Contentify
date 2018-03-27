<?php

namespace App\Modules\Events\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Events\Providers\RouteServiceProvider');

        Lang::addNamespace('events', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('events', realpath(__DIR__.'/../Resources/Views'));
    }

}