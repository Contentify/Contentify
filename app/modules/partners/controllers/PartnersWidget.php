<?php namespace App\Modules\Partners\Controllers;

use App\Modules\Partners\Models\Partners;
use DB, View, Widget;

class PartnersWidget extends Widget {

    public function render($parameters = array())
    {
        $type = 0;

        if (isset($parameters['type'])) $type = $parameters['type'];

        $partners = Partners::whereType($type)->get();

        return View::make('partners::widget', compact('partners'))->render();
    }

}