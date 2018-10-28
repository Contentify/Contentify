<?php

namespace App\Modules\Polls\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Polls\Providers\RouteServiceProvider');

        Lang::addNamespace('polls', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('polls', realpath(__DIR__.'/../Resources/Views'));
    }

}