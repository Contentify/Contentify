<?php

namespace App\Modules\Navigations\Http\Controllers;

use InvalidArgumentException;
use View;
use Widget;

class BreadcrumbWidget extends Widget
{

    public function render(array $parameters = array())
    {
        if (isset($parameters['breadcrumb'])) {
            $links = $parameters['breadcrumb'];
            
            return View::make('navigations::breadcrumb_widget', compact('links'))->render();
        } else {
            throw new InvalidArgumentException('Breadcrumb Widget: Missing array with breadcrumb links!');
        }
    }

}
