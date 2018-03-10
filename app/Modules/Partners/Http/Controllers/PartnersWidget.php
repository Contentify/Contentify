<?php 

namespace App\Modules\Partners\Http\Controllers;

use App\Modules\Partners\Partner;
use App\Modules\Partners\Partnercat;
use View, Widget;

class PartnersWidget extends Widget {

    public function render(array $parameters = array())
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

        $partners = Partner::wherePartnercatId($categoryId)->published()->orderBy('position', 'ASC')->get();

        return View::make('partners::widget', compact('partners'))->render();
    }

}