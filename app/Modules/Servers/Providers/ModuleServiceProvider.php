<?php 

namespace App\Modules\Servers\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Servers\Providers\RouteServiceProvider');

        Lang::addNamespace('servers', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('servers', realpath(__DIR__.'/../Resources/Views'));
    }

}