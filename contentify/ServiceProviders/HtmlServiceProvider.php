<?php namespace Contentify\ServiceProviders;

use Collective\Html\HtmlServiceProvider as OriginalHtmlServiceProvider;
use Contentify\HtmlBuilder;
use Contentify\FormBuilder;

class HtmlServiceProvider extends OriginalHtmlServiceProvider {
    
    /**
     * Register the HTML builder instance.
     *
     * @return void
     */
    protected function registerHtmlBuilder()
    {
        $this->app->singleton('html', function($app)
        {
            return new HtmlBuilder($app['url']);
        });
    }
 
    /**
     * Register the form builder instance.
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        $this->app->singleton('form', function($app)
        {
            $form = new FormBuilder($app['html'], $app['url'], $app['session.store']->getToken());

            return $form->setSessionStore($app['session.store']);
        });
    }

}