<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\TraderRegistered;
use App\Events\DebtCreated;
use App\Events\PaymentMade;
use App\Listeners\SendTraderWelcomeSms;
use App\Listeners\SendDebtNotificationSms;
use App\Listeners\SendPaymentConfirmationSms;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TraderRegistered::class => [
            SendTraderWelcomeSms::class,
        ],
        DebtCreated::class => [
            SendDebtNotificationSms::class,
        ],
        PaymentMade::class => [
            SendPaymentConfirmationSms::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
