<?php namespace App\Modules\Navi\Controllers;

use Exception, View, Widget;

class BreadcrumbWidget extends Widget {

    public function render($parameters = array())
    {
        if (sizeof($parameters) > 0) {
            $links = current($parameters);
    
            return View::make('navi::breadcrumb_widget', compact('links'))->render();
        } else {
            throw new Exception('Breadcrumb Widget: Missing array with breadcrumb links!');
        }
    }

}