<?php

namespace App\Modules\Friends\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Friends\Providers\RouteServiceProvider');

        Lang::addNamespace('friends', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('friends', realpath(__DIR__.'/../Resources/Views'));
    }

}