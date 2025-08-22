<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\License;
use App\Services\SmsService;
use App\Services\ControlNumberService;
use Carbon\Carbon;

class SendLicenseRenewalReminders extends Command
{
    protected $signature = 'sms:license-renewal-reminders {--days=30 : Days before expiry to send reminder}';
    protected $description = 'Send SMS reminders for licenses approaching expiry';

    protected $smsService;
    protected $controlNumberService;

    public function __construct(SmsService $smsService, ControlNumberService $controlNumberService)
    {
        parent::__construct();
        $this->smsService = $smsService;
        $this->controlNumberService = $controlNumberService;
    }

    public function handle()
    {
        $daysAhead = (int) $this->option('days');
        $reminderDate = Carbon::now()->addDays($daysAhead)->toDateString();

        // Get licenses expiring in X days
        $expiringLicenses = License::with('trader')
            ->where('status', 'active')
            ->whereDate('expiry_date', $reminderDate)
            ->get();

        $count = 0;
        foreach ($expiringLicenses as $license) {
            // Generate control number for license renewal
            $controlNumber = $this->controlNumberService->generateLicenseControlNumber($license);
            
            // Send renewal reminder SMS in Swahili
            $message = "Mpendwa {$license->trader->owner_name}, leseni yako ya biashara '{$license->trader->business_name}' " .
                      "itaisha tarehe " . Carbon::parse($license->expiry_date)->format('d/m/Y') . ". " .
                      "Tafadhali ifanye upya mapema ili kuepuka vikwazo vya biashara. " .
                      "Lipa ada ya kufanya upya kwa kutumia namba ya udhibiti: {$controlNumber}. " .
                      "Tembelea ofisi za Halmashauri ya Wilaya ya Kilindi kwa msaada zaidi.";
            
            $this->smsService->sendSms(
                $license->trader->phone_number,
                $message,
                'license_renewal_reminder',
                $license->trader->id
            );
            
            $count++;
        }

        $this->info("Sent {$count} license renewal reminders for licenses expiring in {$daysAhead} days.");
        
        return 0;
    }
}
