<?php

namespace App\Modules\Navigations\Http\Controllers;

use App\Modules\Navigations\Navigation;
use Exception, View, Widget;

class NavigationWidget extends Widget {

    public function render($parameters = array())
    {
        $template = 'navigations::navigation_widget';

        if (isset($parameters['template'])) {
            $template = $parameters['template']; 
        } 

        if (isset($parameters['id'])) {
            $navigation = Navigation::findOrFail($parameters['id']);
        } else {
            $navigation = Navigation::firstOrFail();
        }

        $translate = $navigation->translate;

        $items = json_decode($navigation->items);

        return View::make($template, compact('items', 'translate'))->render();
    }

}