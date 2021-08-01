<?php

namespace Contentify\Vendor\Rss;

use Illuminate\Support\ServiceProvider;

class RssServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Rss::class, function ($app) {
            return new Rss();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('rss');
    }

}
