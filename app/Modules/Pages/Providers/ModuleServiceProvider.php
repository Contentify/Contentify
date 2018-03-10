<?php 

namespace App\Modules\Pages\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\Pages\Providers\RouteServiceProvider');

        Lang::addNamespace('pages', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('pages', realpath(__DIR__.'/../Resources/Views'));
    }

}