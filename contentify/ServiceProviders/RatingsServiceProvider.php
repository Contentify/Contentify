<?php

namespace Contentify\ServiceProviders;

use Contentify\Ratings;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class RatingsServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register instance container to the underlying class object
        $this->app->singleton('ratings', function () {
            return new Ratings;
        });

        // Shortcut so we don't need to add an alias in app/config/app.php
        $this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('Ratings', 'Contentify\Facades\Ratings');
        });
    }
    
}