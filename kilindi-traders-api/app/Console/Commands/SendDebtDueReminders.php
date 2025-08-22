<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Debt;
use App\Services\SmsService;
use App\Services\ControlNumberService;
use Carbon\Carbon;

class SendDebtDueReminders extends Command
{
    protected $signature = 'sms:debt-due-reminders {--days=3 : Days before due date to send reminder}';
    protected $description = 'Send SMS reminders for debts approaching due date';

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

        // Get debts that are due in X days
        $upcomingDebts = Debt::with('trader')
            ->where('status', 'pending')
            ->whereDate('due_date', $reminderDate)
            ->get();

        $count = 0;
        foreach ($upcomingDebts as $debt) {
            // Generate control number for payment
            $controlNumber = $this->controlNumberService->generateDebtControlNumber($debt);
            
            // Send reminder SMS in Swahili
            $message = "Mpendwa {$debt->trader->owner_name}, deni lako la TSh " . number_format($debt->amount) . 
                      " kwa Halmashauri ya Wilaya ya Kilindi litakuwa na muda wa kulipwa tarehe " . 
                      Carbon::parse($debt->due_date)->format('d/m/Y') . ". " .
                      "Lipa mapema kwa kutumia namba ya udhibiti: {$controlNumber}. " .
                      "Wasiliana nasi kwa msaada zaidi.";
            
            $this->smsService->sendSms(
                $debt->trader->phone_number,
                $message,
                'debt_due_reminder',
                $debt->trader->id
            );
            
            $count++;
        }

        $this->info("Sent {$count} debt due reminders for debts due in {$daysAhead} days.");
        
        return 0;
    }
}
