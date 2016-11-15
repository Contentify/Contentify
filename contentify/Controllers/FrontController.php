<?php namespace Contentify\Controllers;

use Exception, URL, Input, View, Redirect, Config;

abstract class FrontController extends BaseController {

    /**
     * The layout that should be used for responses.
     * If null the layout of the chosen theme will be loaded.
     * @var string
     */
    protected $layout = null;

    /**
     * Setup the layout used by the controller.
     * 
     * param string $layoutName The name of the layout template file
     * @return void
     */
    protected function setupLayout($layoutName = null)
    {
        if (! $layoutName) {
            $theme = Config::get('app.theme');

            if (! $theme or false) {
                throw new Exception('Error: Could not determine the theme name from the config!');
            }

            $layoutName = $this->layout? $this->layout : lcfirst($theme).'::layout';
        }

        parent::setupLayout($layoutName);

        View::composer($layoutName, function($view)
        { 
            $view->with('moduleName',       $this->moduleName);
            $view->with('controllerName',   $this->controllerName);
        });
    }

    /**
     * Helper action method for searching. All we do here is to redirect with the input.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function search()
    {
        $url = URL::previous();

        return Redirect::to($url)->withInput(Input::only('search'));
    }
    
}
