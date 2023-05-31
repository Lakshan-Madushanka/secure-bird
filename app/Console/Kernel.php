<?php

declare(strict_types=1);

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Storage;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $this->scheduleDeleteInvalidMessageCommand($schedule);
        $schedule->command('message:delete-decrypted-media')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    public function scheduleDeleteInvalidMessageCommand(Schedule $schedule): void
    {
        $outputFilePath = 'logs\schedules\message\delete-invalid.log';

        if ( ! Storage::exists($outputFilePath)) {
            Storage::put($outputFilePath, '');
        }

        $schedule->command('message:delete-invalid')->daily()->appendOutputTo(Storage::path($outputFilePath));
    }
}
