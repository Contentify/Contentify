<?php

namespace App\Modules\Languages\Providers;

use Illuminate\Support\ServiceProvider;
use Lang, View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        Lang::addNamespace('languages', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('languages', realpath(__DIR__.'/../Resources/Views'));
    }

}