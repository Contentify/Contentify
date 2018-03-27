<?php 

namespace App\Modules\Partners\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Partners\Providers\RouteServiceProvider');

        Lang::addNamespace('partners', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('partners', realpath(__DIR__.'/../Resources/Views'));
    }

}