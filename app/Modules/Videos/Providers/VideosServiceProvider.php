<?php namespace App\Modules\Videos\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class VideosServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Videos\Providers\RouteServiceProvider');

        Lang::addNamespace('videos', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('videos', realpath(__DIR__.'/../Resources/Views'));
    }

}