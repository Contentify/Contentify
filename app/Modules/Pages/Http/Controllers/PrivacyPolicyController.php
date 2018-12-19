<?php 

namespace App\Modules\Pages\Http\Controllers;

use App\Modules\Pages\CustomPage;
use FrontController;
use Config;

class PrivacyPolicyController extends FrontController
{

    /**
     * Displays a page with the privacy policy
     * 
     * @return void
     */
    public function index()
    {
        $customPage = new CustomPage();
        $customPage->title = trans('app.privacy_policy');
        $customPage->text = Config::get('app.privacy_policy');

        $this->title($customPage->title);
        $this->pageView('pages::show_custom_page', compact('customPage'));
    }

}
