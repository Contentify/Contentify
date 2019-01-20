<?php 

namespace App\Modules\Visitors\Http\Controllers;

use App\Modules\Visitors\Chart;
use View;
use Widget;

class ChartWidget extends Widget 
{

    public function render(array $parameters = []) : string
    {
        $chart = new Chart();

        return View::make('visitors::chart', compact('chart'))->render();
    }

}
