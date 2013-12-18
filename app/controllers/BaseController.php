<?php

class BaseController extends Controller {

	/**
	 * Constructor call
	 */
	public function __construct()
	{
		
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	/**
	 * Helper. Shortcut for $this->layout->nest(): Adds a view to the main layout.
	 *
	 * @param string $route
	 * @param array|stdClass data
	 */
	final protected function pageView($template = '', $data = array())
	{
		if ($this->layout != NULL) {
			$this->layout->page = View::make($template, $data);
		} else {
			throw new Exception('Error: $this->layout is NULL!');
		}
	}

}