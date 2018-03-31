<?php 

namespace App\Modules\Visitors;

use Carbon;
use DB;

class Chart 
{

    /**
     * The data set in JS array notation
     * @var string
     */
    protected $dataSet;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $days;

    /**
     * The last day of the month that has data
     *
     * @var int
     */
    protected $maxDay;

    public function __construct()
    {
        /*
         * Gather chart data
         */
        $datetime   = time();
        $month      = date('m', $datetime);
        $year       = date('Y', $datetime);
        $dataSet    = '';
        $days       = DB::table('visits')
                        ->select(DB::raw('SUM(user_agents) AS visitors, DAY(visited_at) AS day, visited_at AS date'))
                        ->where(DB::raw('MONTH(visited_at)'), '=', $month)
                        ->where(DB::raw('YEAR(visited_at)'), '=', $year)
                        ->orderBy('day', 'ASC')->groupBy('visited_at')->get();

        for ($i = 1; $i <= 31; $i++) {
            $visitors = 0;

            foreach ($days as $day) {
                if ($day->day == $i) {
                    $visitors       = $day->visitors;
                    $this->maxDay   = $i;
                    $day->date      = new Carbon($day->date); // Replace with a Carbon instance (enables localisation)
                    break;
                }
            }

            $dataSet .= '['.$i.','.$visitors.'], ';
        }
        
        $this->dataSet = substr($dataSet, 0, -2); // Cut the last 2 characters: ", "
        $this->days = $days;
    }

    public function __get($attribute)
    {
        return $this->$attribute;
    }

}