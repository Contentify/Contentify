<?php namespace App\Modules\Comments\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class CommentsServiceProvider extends ServiceProvider {

    public function register()
    {
        Lang::addNamespace('comments', realpath(__DIR__.'/../Resources/Lang'));

        View::addNamespace('comments', realpath(__DIR__.'/../Resources/Views'));
    }

}