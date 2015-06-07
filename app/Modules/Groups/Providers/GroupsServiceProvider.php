<?php namespace App\Modules\Groups\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class GroupsServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register('App\Modules\Groups\Providers\RouteServiceProvider');

        Lang::addNamespace('groups', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('groups', realpath(__DIR__.'/../Resources/Views'));
    }

}