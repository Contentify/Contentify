<?php

namespace App\Modules\Comments\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Comments\Providers\RouteServiceProvider');

        Lang::addNamespace('comments', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('comments', realpath(__DIR__.'/../Resources/Views'));
    }

}
