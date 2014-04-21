<?php namespace App\Modules\Adverts\Controllers;

use App\Modules\Adverts\Models\Partner;
use DB, View, Widget;

class AdvertsWidget extends Widget {

    public function render($parameters = array())
    {
        $type = 0;

        if (isset($parameters['type'])) $type = $parameters['type'];

        $averts = s::whereType($type)->get();

        return View::make('averts::widget', compact('averts'))->render();
    }

}