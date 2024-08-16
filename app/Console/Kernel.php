<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected $commands = [
        \App\Console\Commands\DeleteUnpaidReservations::class,
        // Add other custom commands here if you have any
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('reservations:delete-unpaid')->everyMinute();
        $schedule->command('homereservations:delete-unpaid')->everyMinute();
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
