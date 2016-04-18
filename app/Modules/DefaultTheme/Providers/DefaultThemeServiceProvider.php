<?php namespace App\Modules\DefaultTheme\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class DefaultThemeServiceProvider extends ServiceProvider {

    protected $namespace = 'defaultTheme';

    public function register()
    {
        View::addNamespace($this->namespace, realpath(__DIR__.'/../Resources/Views'));
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../Resources/Assets/css' => public_path('css'),
        ], $this->namespace);
    }

}