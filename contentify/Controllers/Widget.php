<?php namespace Contentify\Controllers;

abstract class Widget {

	/**
	 * Default limit for DB queries
	 */
	const LIMIT = 5;

    /**
     * Abstract. Renders the widget.
     * 
     * @return void
     */
    abstract public function render($parameters = array());
    
}