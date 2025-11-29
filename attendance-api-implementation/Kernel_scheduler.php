<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Services\ETimeOfficeService;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Sync eTime Office data every 10 minutes
        $schedule->call(function () {
            $service = app(ETimeOfficeService::class);
            $synced = $service->syncToday();
            \Log::info("eTime Office sync completed: {$synced} records");
        })->everyTenMinutes()
          ->name('sync-etime-office')
          ->withoutOverlapping();

        // Full month sync once daily at 2 AM
        $schedule->call(function () {
            $service = app(ETimeOfficeService::class);
            $synced = $service->syncCurrentMonth();
            \Log::info("eTime Office monthly sync completed: {$synced} records");
        })->dailyAt('02:00')
          ->name('sync-etime-office-monthly')
          ->withoutOverlapping();

        // Cleanup old attendance data (keep last 90 days)
        $schedule->call(function () {
            $cutoffDate = \Carbon\Carbon::now()->subDays(90)->format('Y-m-d');
            $deleted = \App\Models\RawAttendance::where('DateString_2', '<', $cutoffDate)->delete();
            \Log::info("Cleaned up old attendance data: {$deleted} records");
        })->weekly()
          ->sundays()
          ->at('03:00')
          ->name('cleanup-old-attendance');
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
