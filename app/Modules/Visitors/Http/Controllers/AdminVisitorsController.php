<?php 

namespace App\Modules\Visitors\Http\Controllers;

use App\Modules\Visitors\Chart;
use BackController;

class AdminVisitorsController extends BackController 
{

    protected $icon = 'chart-pie';

    public function index()
    {
        $chart = new Chart();

        $this->pageView('visitors::admin_show', compact('chart'));
    }

}