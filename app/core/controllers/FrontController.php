<?php namespace Contentify;

use View, Redirect;

abstract class FrontController extends BaseController {

    /**
     * The layout that should be used for responses.
     * @var string
     */
    protected $layout = 'frontend.layout_main';

    public function __construct()
    {
        parent::__construct();

        $self = $this;
        View::composer('frontend.layout_main', function($view) use ($self)
        { 
            $view->with('module', $this->module);
            $view->with('controller', $this->controller);
        });
    }

    /**
     * Helper action method for searching. All we do here is to redirect with the input.
          *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function search()
    {
        return Redirect::route(strtolower($this->controller).'.index')->withInput(Input::only('search'));
    }
}