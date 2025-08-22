<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use HasFactory;

    protected $fillable = [
        'trader_id',
        'amount',
        'description',
        'due_date',
        'status',
        'control_number',
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2',
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
