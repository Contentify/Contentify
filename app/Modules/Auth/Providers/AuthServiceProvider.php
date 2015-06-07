<?php namespace App\Modules\Auth\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class AuthServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Auth\Providers\RouteServiceProvider');

        Lang::addNamespace('auth', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('auth', realpath(__DIR__.'/../Resources/Views'));
    }

}