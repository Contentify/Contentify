<?php namespace App\Modules\Adverts\Controllers;

use App\Modules\Adverts\Models\Advert;
use Redirect, FrontController;

class AdvertsController extends FrontController {

    /**
     * Show the website of an advert
     * 
     * @param  int $id The id of the advert
     * @return Redirect
     */
    public function show($id)
    {
        $advert = Advert::findOrFail($id);

        $advert->access_counter++;
        $advert->save();

        return Redirect::to($advert->url); // Go to advert website
    }

}