<?php

namespace App\Modules\Contact\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class ModuleServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Contact\Providers\RouteServiceProvider');

        Lang::addNamespace('contact', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('contact', realpath(__DIR__.'/../Resources/Views'));
    }

}