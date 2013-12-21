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
	 * Shortcut for $this->layout->nest(): Adds a view to the main layout.
	 *
	 * @param string $template
	 * @param array $data
	 */
	protected function pageView($template = '', $data = array())
	{
		if ($this->layout != NULL) {
			$this->layout->page = View::make($template, $data);
		} else {
			throw new Exception('Error: $this->layout is NULL!');
		}
	}

	/**
	 * Adds a message view to the main layout.
	 *
	 * @param string $title
	 * @param string $text
	 */
	protected function message($title, $text = '')
	{
		if ($this->layout != NULL) {
			$this->layout->page = View::make('message', array('title' => $title, 'text' => $text));
		} else {
			throw new Exception('Error: $this->layout is NULL!');
		}
	}

	/**
	 * Inserts a flash message to the main layout.
	 *
	 * @param string $title
	 */
	protected function messageFlash($title)
	{
		Session::flash('_message', $title);
	}
}