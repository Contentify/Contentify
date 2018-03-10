<?php 

namespace App\Modules\Visitors\Http\Controllers;

use App\Modules\Visitors\Chart;
use View, Widget;

class ChartWidget extends Widget {

    public function render($parameters = array())
    {
        $chart = new Chart();

        return View::make('visitors::chart', compact('chart'))->render();
    }

}