<?php namespace App\Modules\Help\Controllers;

use BackController;

class AdminHelpController extends BackController {

    protected $icon = 'help.png';

    public function getIndex()
    {
        $this->pageView('help::admin_index');
    }

    public function getTechnologies()
    {
        $this->pageView('help::admin_technologies');
    }

    public function getInfo()
    {
        $this->pageView('help::admin_info');
    }

}