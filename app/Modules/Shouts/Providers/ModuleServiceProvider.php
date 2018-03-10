<?php 

namespace App\Modules\Shouts\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Shouts\Providers\RouteServiceProvider');

        Lang::addNamespace('shouts', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('shouts', realpath(__DIR__.'/../Resources/Views'));
    }

}