<?php namespace App\Modules\Users\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class UsersServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Users\Providers\RouteServiceProvider');

        Lang::addNamespace('users', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('users', realpath(__DIR__.'/../Resources/Views'));
    }

}