<?php namespace Contentify;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Contentify\Hover;

class HoverServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register instance container to our underlying class object
        $this->app['hover'] = $this->app->share(function($app)
        {
            return new Hover;
        });

        // Shortcut so developers don't need to add an alias in app/config/app.php
        $this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('Hover', 'Contentify\Facades\Hover');
        });
    }
}