<?php namespace App\Modules\Navigations\Controllers;

use Exception, View, Widget;

class BreadcrumbWidget extends Widget {

    public function render($parameters = array())
    {
        if (sizeof($parameters) > 0) {
            $links = current($parameters);
    
            return View::make('navigation::breadcrumb_widget', compact('links'))->render();
        } else {
            throw new Exception('Breadcrumb Widget: Missing array with breadcrumb links!');
        }
    }

}