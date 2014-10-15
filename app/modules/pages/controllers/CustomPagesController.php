<?php namespace App\Modules\Pages\Controllers;

use App\Modules\Pages\Models\CustomPage;
use URL, HTML, FrontController;

class CustomPagesController extends FrontController {

    public function __construct()
    {
        $this->modelName = 'CustomPage';

        parent::__construct();
    }

    /**
     * Show a custom page
     * 
     * @param  int      $id     The ID of the custom page
     * @param  string   $slug   The unique slug
     * @return void
     */
    public function show($id, $slug = null)
    {
        if ($id) {
            $customPage = CustomPage::whereId($id)->published()->firstOrFail();    
        } else {
            $customPage = CustomPage::whereSlug($slug)->published()->firstOrFail();    
        }        

        $hasAccess = (user() and user()->hasAccess('internal'));
        if ($customPage->internal and ! $hasAccess) {
            return $this->message(trans('app.access_denied'));
        }

        $customPage->access_counter++;
        $customPage->save();

        $this->title($customPage->title);
        $this->pageView('pages::show_custom_page', compact('customPage'));
    }

    /**
     * Show a custom page by slug instead of ID
     * 
     * @param  string $slug The unique slug
     * @return void
     */
    public function showBySlug($slug)
    {
        $this->show(null, $slug);
    }

    /**
     * Show the default imprint page that includes
     * infos about the CMS.
     * 
     * @return void
     */
    public function showImprint()
    {
        $imprintText = CustomPage::findOrFail(1)->text;

        $this->pageView('pages::imprint', compact('imprintText'));
    }

}