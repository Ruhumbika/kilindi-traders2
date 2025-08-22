<?php

namespace App\Listeners;

use App\Events\PaymentMade;
use App\Services\SmsService;

class SendPaymentConfirmationSms
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function handle(PaymentMade $event)
    {
        $this->smsService->sendPaymentConfirmation($event->payment->trader, $event->payment->amount);
    }
}
