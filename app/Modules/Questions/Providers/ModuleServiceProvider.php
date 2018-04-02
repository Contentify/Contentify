<?php

namespace App\Modules\Questions\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Questions\Providers\RouteServiceProvider');

        Lang::addNamespace('questions', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('questions', realpath(__DIR__.'/../Resources/Views'));
    }

}