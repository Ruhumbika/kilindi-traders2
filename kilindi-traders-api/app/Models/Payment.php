<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'trader_id',
        'debt_id',
        'license_id',
        'amount',
        'payment_method',
        'transaction_reference',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function trader()
    {
        return $this->belongsTo(Trader::class);
    }

    public function debt()
    {
        return $this->belongsTo(Debt::class);
    }

    public function license()
    {
        return $this->belongsTo(License::class);
    }
}
