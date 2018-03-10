<?php 

namespace App\Modules\Slides\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class ModuleServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Slides\Providers\RouteServiceProvider');

        Lang::addNamespace('slides', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('slides', realpath(__DIR__.'/../Resources/Views'));
    }

}