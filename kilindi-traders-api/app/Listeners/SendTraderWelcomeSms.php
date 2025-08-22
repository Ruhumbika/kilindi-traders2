<?php

namespace App\Listeners;

use App\Events\TraderRegistered;
use App\Services\SmsService;

class SendTraderWelcomeSms
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function handle(TraderRegistered $event)
    {
        $this->smsService->sendWelcomeMessage($event->trader);
    }
}
