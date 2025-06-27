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
        // Update dataset statistics daily at 2 AM
        $schedule->command('datasets:update-stats')
                 ->dailyAt('02:00')
                 ->withoutOverlapping();

        // Generate weekly reports every Monday at 8 AM
        $schedule->command('reports:weekly')
                 ->weeklyOn(1, '08:00')
                 ->withoutOverlapping();

        // Clean up old files weekly on Sunday at 3 AM
        $schedule->command('cleanup:files')
                 ->weeklyOn(0, '03:00')
                 ->withoutOverlapping();

        // Clear expired cache entries daily at 1 AM
        $schedule->command('cache:prune-stale-tags')
                 ->dailyAt('01:00');

        // Backup database daily at 4 AM (if backup package is installed)
        // $schedule->command('backup:run')
        //          ->dailyAt('04:00')
        //          ->withoutOverlapping();
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