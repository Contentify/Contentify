<?php

namespace App\Modules\Awards\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Awards\Providers\RouteServiceProvider');

        Lang::addNamespace('awards', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('awards', realpath(__DIR__.'/../Resources/Views'));
    }

}