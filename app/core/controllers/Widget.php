<?php namespace Contentify;

abstract class Widget {

    /**
     * Abstract. Renders the widget.
     * 
     * @return void
     */
    abstract public function render($parameters = array());
    
}