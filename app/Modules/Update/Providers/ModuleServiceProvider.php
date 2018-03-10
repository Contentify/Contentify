<?php 

namespace App\Modules\Update\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class ModuleServiceProvider extends ServiceProvider 
{

    public function register()
    {
        App::register('App\Modules\Update\Providers\RouteServiceProvider');

        Lang::addNamespace('update', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('update', realpath(__DIR__.'/../Resources/Views'));
    }

}