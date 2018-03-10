<?php

namespace App\Modules\Navigations\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Navigations\Providers\RouteServiceProvider');

        Lang::addNamespace('navigations', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('navigations', realpath(__DIR__.'/../Resources/Views'));
    }

}