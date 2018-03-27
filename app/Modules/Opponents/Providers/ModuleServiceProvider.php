<?php

namespace App\Modules\Opponents\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Opponents\Providers\RouteServiceProvider');

        Lang::addNamespace('opponents', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('opponents', realpath(__DIR__.'/../Resources/Views'));
    }

}