<?php namespace App\Modules\Dashboard\Controllers;

class AdminDashboardController extends \BackController {

    public function getIndex()
    {
        $this->pageView('dashboard::index');
    }

}