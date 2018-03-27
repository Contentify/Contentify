<?php

namespace App\Modules\Matches\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Matches\Providers\RouteServiceProvider');

        Lang::addNamespace('matches', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('matches', realpath(__DIR__.'/../Resources/Views'));
    }

}