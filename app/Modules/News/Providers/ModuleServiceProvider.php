<?php

namespace App\Modules\News\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\News\Providers\RouteServiceProvider');

        Lang::addNamespace('news', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('news', realpath(__DIR__.'/../Resources/Views'));
    }

}