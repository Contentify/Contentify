<?php namespace App\Modules\Partners\Controllers;

use App\Modules\Partners\Models\Partner;
use DB, View, Widget;

class PartnersWidget extends Widget {

    public function render($parameters = array())
    {
        $layoutType = 0;

        if (isset($parameters['layoutType'])) $layoutType = $parameters['layoutType'];

        $partners = Partner::whereLayoutType($layoutType)->get();

        return View::make('partners::widget', compact('partners'))->render();
    }

}