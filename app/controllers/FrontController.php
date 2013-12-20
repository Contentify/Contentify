<?php

class FrontController extends BaseController {
	/**
     * The layout that should be used for responses.
     */
    protected $layout = 'frontend';

	/**
	 * Constructor call
	 */
	public function __construct()
	{
		parent::__construct();

		// Enable auto CSRF protection
		$this->beforeFilter('csrf', array('on' => 'post'));
	}
}