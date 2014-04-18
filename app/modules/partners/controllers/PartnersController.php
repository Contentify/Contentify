<?php namespace App\Modules\Partners\Controllers;

use App\Modules\Partners\Models\Partner;
use Redirect, FrontController;

class TeamsController extends FrontController {

    public function index()
    {
        $partners = Partner::orderBy('position', 'ASC')->get();

        $this->pageView('partners::index', compact('partners'));
    }

    /**
     * Show the website of a partner
     * 
     * @param  int $id The id of the partner
     * @return Redirect
     */
    public function website($id)
    {
        $partner = Partner::findOrFail($id);

        return Redirect::to($partner->url);
    }

}