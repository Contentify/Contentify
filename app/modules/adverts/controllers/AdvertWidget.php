<?php namespace App\Modules\Adverts\Controllers;

use App\Modules\Adverts\Models\Advert;
use Config, DB, View, Widget;

class AdvertWidget extends Widget {

    public function render($parameters = array())
    {
        $layoutType = 0;

        if (isset($parameters['layoutType'])) $type = $parameters['layoutType'];

        /*
         * Create the id attribute for the (div) container of the advert.
         * The idea is to create a "random" name instead of something like
         * "advert-top" so it's harder to use an ad blocker.
         * Different types have different IDs.
         */
        $key            = substr(Config::get('app.key'), 0, 10);
        $salt           = 'f2h8wqhdfn'; // Even more salt - you may change this value!
        $containerId    = substr(md5($layoutType.$key.$salt), 0, 5);

        /*
         * Ensure $containerId starts with an alphabetic character
         */
        if (! ctype_alpha(substr($containerId, 0, 1))) {
            $containerId = (chr(ord(substr($containerId, 0, 1)) % 26 + 97)).$containerId;
        }

        $advert = Advert::orderBy(DB::raw('RAND()'))->whereLayoutType($layoutType)->first();

        if ($advert) {
            return View::make('adverts::widget', compact('advert', 'containerId'))->render();
        }
    }

}