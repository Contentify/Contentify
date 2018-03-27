<?php

namespace App\Modules\Adverts\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Adverts\Providers\RouteServiceProvider');

        Lang::addNamespace('adverts', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('adverts', realpath(__DIR__.'/../Resources/Views'));
    }

}