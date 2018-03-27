<?php

namespace Contentify\ServiceProviders;

use Contentify\ModuleRoute;
use Illuminate\Support\ServiceProvider;

class ModuleRouteServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register instance container to the underlying class object
        $this->app->singleton('moduleRoute', function () {
            return new ModuleRoute;
        });

        /*
         * Note: Using AliasLoader to create an alias won't work due to
         * too late the binding. Instead we have to register the alias in the config file.
         */
    }
    
}