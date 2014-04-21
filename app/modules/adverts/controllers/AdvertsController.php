<?php namespace App\Modules\Adverts\Controllers;

use App\Modules\Adverts\Models\Advert;
use Redirect, FrontController;

class AdvertsController extends FrontController {

    /**
     * Show the website of a partner
     * 
     * @param  int $id The id of the partner
     * @return Redirect
     */
    public function show($id)
    {
        $partner = Partner::findOrFail($id);

        $partner->access_counter++;
        $partner->save();

        return Redirect::to($partner->url); // Go to partner website
    }

}