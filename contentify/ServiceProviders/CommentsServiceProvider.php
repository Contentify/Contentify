<?php

namespace Contentify\ServiceProviders;

use Contentify\Comments;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class CommentsServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register instance container to the underlying class object
        $this->app->singleton('comments', function () {
            return new Comments;
        });

        // Shortcut so we don't need to add an alias in app/config/app.php
        $this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('Comments', 'Contentify\Facades\Comments');
        });
    }
    
}