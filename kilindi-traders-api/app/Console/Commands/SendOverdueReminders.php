<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Debt;
use App\Services\SmsService;
use Carbon\Carbon;

class SendOverdueReminders extends Command
{
    protected $signature = 'sms:overdue-reminders';
    protected $description = 'Send SMS reminders for overdue debts';

    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        parent::__construct();
        $this->smsService = $smsService;
    }

    public function handle()
    {
        // Get debts that are overdue
        $overdueDebts = Debt::with('trader')
            ->where('status', 'pending')
            ->where('due_date', '<', Carbon::now())
            ->get();

        $count = 0;
        foreach ($overdueDebts as $debt) {
            // Update debt status to overdue
            $debt->update(['status' => 'overdue']);
            
            // Send overdue notice
            $this->smsService->sendOverdueNotice(
                $debt->trader, 
                $debt->amount, 
                $debt->due_date
            );
            
            $count++;
        }

        $this->info("Sent {$count} overdue reminders and updated debt statuses.");
        
        return 0;
    }
}
