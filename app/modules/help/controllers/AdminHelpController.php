<?php namespace App\Modules\Help\Controllers;

class AdminHelpController extends \BackController {

    protected $icon = 'help.png';

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