<?php

namespace App\Modules\Navigations\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Navigations\Providers\RouteServiceProvider');

        Lang::addNamespace('navigations', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('navigations', realpath(__DIR__.'/../Resources/Views'));
    }

}