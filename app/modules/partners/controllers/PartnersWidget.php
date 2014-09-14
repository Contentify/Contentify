<?php namespace App\Modules\Partners\Controllers;

use App\Modules\Partners\Models\Partner;
use App\Modules\Partners\Models\Partnercat;
use DB, View, Widget;

class PartnersWidget extends Widget {

    public function render($parameters = array())
    {
        if (isset($parameters['categoryId'])) {
        	$categoryId = $parameters['categoryId'];
        } else {
        	$partnercat = Partnercat::first();
        	if ($partnercat) {
        		$categoryId = $partnercat->id;
        	} else {
        		$categoryId = 0;
        	}
        }

        $partners = Partner::wherePartnercatId($categoryId)->get();

        return View::make('partners::widget', compact('partners'))->render();
    }

}