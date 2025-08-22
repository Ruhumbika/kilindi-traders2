<?php

namespace App\Events;

use App\Models\Debt;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DebtCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $debt;

    public function __construct(Debt $debt)
    {
        $this->debt = $debt;
    }
}
