<?php

namespace Scavenger\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Scavenger\Console\Commands\Inspire::class,
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
        $schedule->command('inspire')
            ->cron('* * * * * *')
            ->sendOutputTo('/home4/grayson/public_html/output.txt');
*/

		$schedule->call(function() {
			redirect('cron');
		})->cron('* * * * * *')
		->sendOutputTo('/home4/grayson/public_html/output.txt');
		
    }
}
