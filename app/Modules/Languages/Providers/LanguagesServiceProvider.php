<?php namespace App\Modules\Languages\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class LanguagesServiceProvider extends ServiceProvider {

    public function register()
    {
        Lang::addNamespace('languages', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('languages', realpath(__DIR__.'/../Resources/Views'));
    }

}