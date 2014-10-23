<?php namespace App\Modules\Visitors\Controllers;

use DB, View, Widget;

class VisitorsWidget extends Widget {

    public function render($parameters = array())
    {
        // Use SUM() so we will always get a result, even if there aren't any rows for today.
        $today = DB::table('visits')->select(DB::raw('SUM(user_agents) AS user_agents'))
            ->whereVisitedAt(DB::raw('CURRENT_DATE'))
            ->remember(5)->get()[0]->user_agents;

        if (! $today) $today = 0;

        $yesterday = DB::table('visits')->select(DB::raw('SUM(user_agents) AS user_agents'))
            ->whereVisitedAt(DB::raw('SUBDATE(CURRENT_DATE, 1)'))
            ->remember(5)->get()[0]->user_agents;  

        if (! $yesterday) $yesterday = 0;

        $month = DB::table('visits')->select(DB::raw('SUM(user_agents) AS user_agents'))
            ->where(DB::raw('MONTH(visited_at)'), '=', DB::raw('MONTH(CURRENT_DATE)'))
            ->where(DB::raw('YEAR(visited_at)'), '=', DB::raw('YEAR(CURRENT_DATE)'))
            ->remember(30)->get()[0]->user_agents;

        $total = DB::table('visits')->select(DB::raw('SUM(user_agents) AS user_agents'))
            ->remember(60)->get()[0]->user_agents;

        return View::make('visitors::widget_visitors', compact('today', 'yesterday', 'month', 'total'))->render();
    }

}