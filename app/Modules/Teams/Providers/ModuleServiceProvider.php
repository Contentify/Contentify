<?php 

namespace App\Modules\Teams\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class ModuleServiceProvider extends ServiceProvider 
{

    public function register()
    {
        App::register('App\Modules\Teams\Providers\RouteServiceProvider');

        Lang::addNamespace('teams', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('teams', realpath(__DIR__.'/../Resources/Views'));
    }

}