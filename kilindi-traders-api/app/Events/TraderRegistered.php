<?php

namespace App\Events;

use App\Models\Trader;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TraderRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $trader;

    public function __construct(Trader $trader)
    {
        $this->trader = $trader;
    }
}
