<?php namespace App\Modules\Help\Controllers;

class AdminHelpController extends \BackController {

    public function getIndex()
    {
        $this->pageView('help::index');
    }

    public function getTechnologies()
    {
        $this->pageView('help::technologies');
    }

    public function getInfo()
    {
        $this->pageView('help::info');
    }

}