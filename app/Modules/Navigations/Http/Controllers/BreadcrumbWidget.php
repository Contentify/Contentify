<?php

namespace App\Modules\Navigations\Http\Controllers;

use Exception, View, Widget;

class BreadcrumbWidget extends Widget {

    public function render(array $parameters = array())
    {
        if (isset($parameters['breadcrumb'])) {
            $links = $parameters['breadcrumb'];
            
            return View::make('navigations::breadcrumb_widget', compact('links'))->render();
        } else {
            throw new Exception('Breadcrumb Widget: Missing array with breadcrumb links!');
        }
    }

}