<?php

namespace App\Modules\Downloads\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Downloads\Providers\RouteServiceProvider');

        Lang::addNamespace('downloads', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('downloads', realpath(__DIR__.'/../Resources/Views'));
    }

}