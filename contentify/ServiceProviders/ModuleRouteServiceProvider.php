<?php

namespace Contentify\ServiceProviders;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Contentify\ModuleRoute;

class ModuleRouteServiceProvider extends ServiceProvider {

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