<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trader extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'owner_name',
        'phone_number',
        'email',
        'business_location',
        'business_type',
        'control_number',
        'license_number',
        'license_expiry_date',
    ];

    protected $casts = [
        'license_expiry_date' => 'date',
    ];

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

    public function licenses()
    {
        return $this->hasMany(License::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
