<?php namespace Contentify\Controllers;

abstract class Widget {

    /**
     * Abstract. Renders the widget.
     * 
     * @return void
     */
    abstract public function render($parameters = array());
    
}