<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
//        $schedule->command('report:daily-cases')->dailyAt('19:00'); // Run at 19:00 daily
//        $schedule->command('report:daily-cases')->dailyAt('11:00');
//        $schedule->command('report:daily-cases')->dailyAt('11:00')->withoutOverlapping();
        $schedule->command('report:daily-cases')->twiceDaily(12, 18)->withoutOverlapping();
//        $schedule->command('report:test-log')->everyThreeMinutes();

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
