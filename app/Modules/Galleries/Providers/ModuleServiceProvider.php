<?php

namespace App\Modules\Galleries\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Galleries\Providers\RouteServiceProvider');

        Lang::addNamespace('galleries', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('galleries', realpath(__DIR__.'/../Resources/Views'));
    }

}