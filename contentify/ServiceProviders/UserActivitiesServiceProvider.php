<?php

namespace Contentify\ServiceProviders;

use Contentify\UserActivities;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class UserActivitiesServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register instance container to the underlying class object
        $this->app->singleton('userActivities', function () {
            return new UserActivities;
        });

        // Shortcut so we don't need to add an alias in app/config/app.php
        $this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('UserActivities', 'Contentify\Facades\UserActivities');
        });
    }
    
}