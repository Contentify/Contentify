<?php

namespace App\Modules\Galleries\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class ModuleServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Galleries\Providers\RouteServiceProvider');

        Lang::addNamespace('galleries', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('galleries', realpath(__DIR__.'/../Resources/Views'));
    }

}