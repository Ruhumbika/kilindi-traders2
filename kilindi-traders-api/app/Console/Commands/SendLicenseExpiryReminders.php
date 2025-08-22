<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\License;
use App\Services\SmsService;
use Carbon\Carbon;

class SendLicenseExpiryReminders extends Command
{
    protected $signature = 'sms:license-expiry-reminders';
    protected $description = 'Send SMS reminders for licenses expiring within 30 days';

    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        parent::__construct();
        $this->smsService = $smsService;
    }

    public function handle()
    {
        // Get licenses expiring within 30 days
        $expiringLicenses = License::with('trader')
            ->where('status', 'active')
            ->whereBetween('expiry_date', [Carbon::now(), Carbon::now()->addDays(30)])
            ->get();

        $count = 0;
        foreach ($expiringLicenses as $license) {
            $this->smsService->sendLicenseExpiry(
                $license->trader, 
                $license->expiry_date,
                $license->control_number
            );
            
            $count++;
        }

        $this->info("Sent {$count} license expiry reminders.");
        
        return 0;
    }
}
