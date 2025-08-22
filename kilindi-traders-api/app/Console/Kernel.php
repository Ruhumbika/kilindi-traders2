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
        // Send debt due reminders daily at 8 AM (3 days before due date)
        $schedule->command('sms:debt-due-reminders --days=3')->dailyAt('08:00');
        
        // Send overdue reminders daily at 9 AM
        $schedule->command('sms:overdue-reminders')->dailyAt('09:00');
        
        // Send license renewal reminders daily at 10 AM (30 days before expiry)
        $schedule->command('sms:license-renewal-reminders --days=30')->dailyAt('10:00');
        
        // Send license expiry reminders weekly on Mondays at 11 AM (7 days before expiry)
        $schedule->command('sms:license-expiry-reminders')->weeklyOn(1, '11:00');
    }

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SendOverdueReminders::class,
        Commands\SendLicenseExpiryReminders::class,
        Commands\SendDebtDueReminders::class,
        Commands\SendLicenseRenewalReminders::class,
    ];

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
