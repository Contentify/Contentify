<?php 

namespace App\Modules\Shouts\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Shouts\Providers\RouteServiceProvider');

        Lang::addNamespace('shouts', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('shouts', realpath(__DIR__.'/../Resources/Views'));
    }

}