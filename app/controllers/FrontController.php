<?php

class FrontController extends BaseController {
	/**
     * The layout that should be used for responses.
     */
    protected $layout = 'frontend.index';

	/**
	 * Constructor call
	 */
	public function __construct()
	{
		parent::__construct();
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