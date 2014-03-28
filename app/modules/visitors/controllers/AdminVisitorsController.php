<?php namespace App\Modules\Visitors\Controllers;

use Carbon, DB, BackController;

class AdminVisitorsController extends BackController {

    protected $icon = 'chart_bar.png';

    public function index()
    {
        /*
         * Gather chart data
         */
        $datetime   = time();
        $month      = date('m', $datetime);
        $year       = date('Y', $datetime);
        $dataSet    = '';
        $days       = DB::table('visits')->select(DB::raw('SUM(user_agents) AS visitors, DAY(visited_at) AS day, visited_at AS date'))
                        ->where(DB::raw('MONTH(visited_at)'), '=', $month)
                        ->where(DB::raw('YEAR(visited_at)'), '=', $year)
                        ->orderBy('day', 'ASC')->groupBy('visited_at')->get();

        for ($i = 1; $i < 31; $i++)
        {
            $visitors = 0;
            foreach ($days as $day)
            {
                if ($day->day == $i)
                {
                    $visitors   = $day->visitors;
                    $maxDay     = $i;
                    $day->date  = new Carbon($day->date); // Replcae with a Carbon instance - also enables localisation.
                    break;
                }
            }
            $dataSet .= '['.$i.','.$visitors.'], ';
        }
        $dataSet = substr($dataSet, 0, -2); // Cut the last 2 characters: ", "

        $this->pageView('visitors::admin_show', compact('days', 'dataSet', 'maxDay'));
    }

}