<?php 

namespace App\Modules\Search\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Search\Providers\RouteServiceProvider');

        Lang::addNamespace('search', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('search', realpath(__DIR__.'/../Resources/Views'));
    }

}