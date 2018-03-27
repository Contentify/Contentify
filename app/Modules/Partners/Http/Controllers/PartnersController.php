<?php 

namespace App\Modules\Partners\Http\Controllers;

use App\Modules\Partners\Partner;
use FrontController;
use Illuminate\Http\RedirectResponse;
use Redirect;

class PartnersController extends FrontController
{

    public function index()
    {
        $partners = Partner::orderBy('position', 'ASC')->published()->get();

        $this->pageView('partners::index', compact('partners'));
    }

    /**
     * Navigate to the website of a partner
     * 
     * @param  int $id The id of the partner
     * @return RedirectResponse
     */
    public function url($id)
    {
        $partner = Partner::published()->findOrFail($id);

        $partner->access_counter++;
        $partner->save();

        return Redirect::to($partner->url); // Go to partner website
    }

}