<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'Contentify\Commands\MakeFormCommand',
        'ChrisKonnertz\Jobs\JobsCommand',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*
         * This is just an example! Do not activate this.
         * NOTE: We recommend to use Contentify jobs instead of Laravel jobs!
         */
        //$schedule->command('jobs')->everyMinute();
    }

}
