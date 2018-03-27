<?php 

namespace App\Modules\Tournaments\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider 
{

    public function register()
    {
        App::register('App\Modules\Tournaments\Providers\RouteServiceProvider');

        Lang::addNamespace('tournaments', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('tournaments', realpath(__DIR__.'/../Resources/Views'));
    }

}