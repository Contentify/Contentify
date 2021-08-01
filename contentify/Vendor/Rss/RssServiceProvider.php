<?php

namespace Contentify\Vendor\Rss;

use Illuminate\Support\ServiceProvider;

class RssServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('rss', function ($app) {
            return new Rss();
        });
    }

}
