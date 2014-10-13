<?php namespace App\Modules\Visitors\Controllers;

use App\Modules\Visitors\Models\Chart;
use View, Widget;

class ChartWidget extends Widget {

    public function render($parameters = array())
    {
        $chart = new Chart();

        return View::make('visitors::chart', compact('chart'))->render();
    }

}