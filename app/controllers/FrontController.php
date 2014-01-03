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
}