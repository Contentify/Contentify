<?php 

namespace App\Modules\Videos\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider 
{

    public function register()
    {
        App::register('App\Modules\Videos\Providers\RouteServiceProvider');

        Lang::addNamespace('videos', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('videos', realpath(__DIR__.'/../Resources/Views'));
    }

}