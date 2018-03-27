<?php

namespace Contentify\ServiceProviders;

use Contentify\ContentFilter;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class ContentFilterServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register instance container to the underlying class object
        $this->app->singleton('contentFilter', function () {
            return new ContentFilter;
        });

        // Shortcut so we don't need to add an alias in app/config/app.php
        $this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('ContentFilter', 'Contentify\Facades\ContentFilter');
        });
    }
    
}