<?php 

namespace App\Modules\Streams\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Streams\Providers\RouteServiceProvider');

        Lang::addNamespace('streams', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('streams', realpath(__DIR__.'/../Resources/Views'));
    }

}