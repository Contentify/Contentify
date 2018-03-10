<?php 

namespace App\Modules\Roles\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class ModuleServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Roles\Providers\RouteServiceProvider');

        Lang::addNamespace('roles', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('roles', realpath(__DIR__.'/../Resources/Views'));
    }

}