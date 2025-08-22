<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'trader_id',
        'license_type',
        'license_number',
        'issue_date',
        'expiry_date',
        'fee_amount',
        'status',
        'control_number',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'fee_amount' => 'decimal:2',
    ];

    public function trader()
    {
        return $this->belongsTo(Trader::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
