<?php namespace App\Modules\Downloads\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class DownloadsServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Downloads\Providers\RouteServiceProvider');

        Lang::addNamespace('downloads', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('downloads', realpath(__DIR__.'/../Resources/Views'));
    }

}