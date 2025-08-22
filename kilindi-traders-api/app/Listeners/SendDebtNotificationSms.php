<?php

namespace App\Listeners;

use App\Events\DebtCreated;
use App\Services\SmsService;

class SendDebtNotificationSms
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function handle(DebtCreated $event)
    {
        $this->smsService->sendDebtReminder(
            $event->debt->trader, 
            $event->debt->amount, 
            $event->debt->control_number
        );
    }
}
