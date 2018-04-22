<?php 

namespace App\Modules\Partners\Http\Controllers;

use App\Modules\Partners\Partner;
use App\Modules\Partners\PartnerCat;
use View;
use Widget;

class PartnersWidget extends Widget
{

    public function render(array $parameters = array())
    {
        if (isset($parameters['categoryId'])) {
            $categoryId = $parameters['categoryId'];
        } else {
            $partnerCat = PartnerCat::first();
            if ($partnerCat) {
                $categoryId = $partnerCat->id;
            } else {
                $categoryId = 0;
            }
        }

        $partners = Partner::wherePartnerCatId($categoryId)->published()->orderBy('position', 'ASC')->get();

        return View::make('partners::widget', compact('partners'))->render();
    }

}