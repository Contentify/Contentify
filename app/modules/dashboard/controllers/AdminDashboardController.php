<?php namespace App\Modules\Dashboard\Controllers;

class AdminDashboardController extends \BackController {

    protected $icon = 'house.png';

    public function getIndex()
    {
        $this->pageView('dashboard::index');
    }

}