<?php 

namespace App\Modules\Visitors\Http\Controllers;

use Cache;
use DB;
use View;
use Widget;

class VisitorsWidget extends Widget 
{

    public function render(array $parameters = array())
    {
        // Use SUM() so we will always get a result, even if there aren't any rows for today.
        $today = Cache::remember('visitors.widget.today', 5, function()
        {
            $today = DB::table('visits')->select(DB::raw('SUM(user_agents) AS user_agents'))
            ->whereVisitedAt(DB::raw('CURRENT_DATE'))->get()[0]->user_agents;

            return $today ? $today : 0;
        });

        $yesterday = Cache::remember('visitors.widget.yesterday', 5, function()
        {
            $yesterday = DB::table('visits')->select(DB::raw('SUM(user_agents) AS user_agents'))
            ->whereVisitedAt(DB::raw('SUBDATE(CURRENT_DATE, 1)'))->get()[0]->user_agents;

            return $yesterday ? $yesterday : 0;
        });

        $month = Cache::remember('visitors.widget.month', 30, function()
        {
            return DB::table('visits')->select(DB::raw('SUM(user_agents) AS user_agents'))
            ->where(DB::raw('MONTH(visited_at)'), '=', DB::raw('MONTH(CURRENT_DATE)')) // Note: whereMonth won't work!
            ->where(DB::raw('YEAR(visited_at)'), '=', DB::raw('YEAR(CURRENT_DATE)'))
            ->get()[0]->user_agents;
        });

        $total = Cache::remember('visitors.widget.total', 60, function()
        {
            return DB::table('visits')->select(DB::raw('SUM(user_agents) AS user_agents'))
            ->get()[0]->user_agents;
        });

        return View::make('visitors::widget', compact('today', 'yesterday', 'month', 'total'))->render();
    }

}