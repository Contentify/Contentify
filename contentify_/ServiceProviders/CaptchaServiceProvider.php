<?php namespace Contentify\ServiceProviders;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Contentify\Captcha;

class CaptchaServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register instance container to the underlying class object
        $this->app['captcha'] = $this->app->share(function($app)
        {
            return new Captcha;
        });

        // Shortcut so we don't need to add an alias in app/config/app.php
        $this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('Captcha', 'Contentify\Facades\Captcha');
        });
    }
    
}