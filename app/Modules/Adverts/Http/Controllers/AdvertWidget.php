<?php

namespace App\Modules\Adverts\Http\Controllers;

use App\Modules\Adverts\Advert;
use App\Modules\Adverts\AdvertCat;
use Config;
use DB;
use View;
use Widget;

class AdvertWidget extends Widget
{

    public function render(array $parameters = array())
    {
        if (isset($parameters['categoryId'])) {
            $categoryId = $parameters['categoryId'];
        } else {
            $advertCat = AdvertCat::first();
            if ($advertCat) {
                $categoryId = $advertCat->id;
            } else {
                $categoryId = 0;
            }
        }

        /*
         * Create the id attribute for the (div) container of the advert.
         * The idea is to create a "random" name instead of something like
         * "advert-top" so it's harder to use an ad blocker.
         * Different types have different IDs.
         */
        $key            = substr(Config::get('app.key'), 0, 10);
        $salt           = 'f2h8wqhdfn'; // Even more salt - you may change this value!
        $containerId    = substr(md5($categoryId.$key.$salt), 0, 5);

        /*
         * Ensure $containerId starts with an alphabetic character
         */
        if (! ctype_alpha(substr($containerId, 0, 1))) {
            $containerId = (chr(ord(substr($containerId, 0, 1)) % 26 + 97)).$containerId;
        }

        $advert = Advert::orderBy(DB::raw('RAND()'))->published()->whereAdvertCatId($categoryId)->first();

        if ($advert) {
            return View::make('adverts::widget', compact('advert', 'containerId'))->render();
        }
    }

}