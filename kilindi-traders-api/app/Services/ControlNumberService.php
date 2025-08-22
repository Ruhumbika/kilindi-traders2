<?php

namespace App\Services;

use App\Models\Debt;
use App\Models\License;
use Carbon\Carbon;

class ControlNumberService
{
    public function generateControlNumber($type, $amount, $traderId = null)
    {
        // KDC = Kilindi District Council
        $prefix = 'KDC';
        
        // Type codes: D=Debt, L=License, F=Fee
        $typeCode = match($type) {
            'debt' => 'D',
            'license' => 'L',
            'fee' => 'F',
            default => 'P'
        };
        
        // Year and month
        $yearMonth = Carbon::now()->format('ym');
        
        // Sequential number (6 digits)
        $sequence = str_pad($this->getNextSequence($type), 6, '0', STR_PAD_LEFT);
        
        // Checksum (last 2 digits of amount)
        $checksum = str_pad($amount % 100, 2, '0', STR_PAD_LEFT);
        
        return $prefix . $typeCode . $yearMonth . $sequence . $checksum;
    }

    public function generateDebtControlNumber(Debt $debt)
    {
        $controlNumber = $this->generateControlNumber('debt', $debt->amount, $debt->trader_id);
        
        // Update debt with control number
        $debt->update(['control_number' => $controlNumber]);
        
        return $controlNumber;
    }

    public function generateLicenseControlNumber(License $license)
    {
        $controlNumber = $this->generateControlNumber('license', $license->fee_amount, $license->trader_id);
        
        // Update license with control number
        $license->update(['control_number' => $controlNumber]);
        
        return $controlNumber;
    }

    private function getNextSequence($type)
    {
        // Get the last control number for this type and year-month
        $prefix = 'KDC' . match($type) {
            'debt' => 'D',
            'license' => 'L', 
            'fee' => 'F',
            default => 'P'
        } . Carbon::now()->format('ym');

        // Find the highest sequence number for this period
        $lastNumber = 0;
        
        if ($type === 'debt') {
            $lastDebt = Debt::where('control_number', 'like', $prefix . '%')
                ->orderBy('control_number', 'desc')
                ->first();
            if ($lastDebt && $lastDebt->control_number) {
                $lastNumber = (int) substr($lastDebt->control_number, -8, 6);
            }
        } elseif ($type === 'license') {
            $lastLicense = License::where('control_number', 'like', $prefix . '%')
                ->orderBy('control_number', 'desc')
                ->first();
            if ($lastLicense && $lastLicense->control_number) {
                $lastNumber = (int) substr($lastLicense->control_number, -8, 6);
            }
        }
        
        return $lastNumber + 1;
    }

    public function validateControlNumber($controlNumber)
    {
        // Validate format: KDC + TypeCode + YYMM + 6digits + 2checksum = 15 chars
        if (strlen($controlNumber) !== 15) {
            return false;
        }
        
        if (substr($controlNumber, 0, 3) !== 'KDC') {
            return false;
        }
        
        return true;
    }

    public function getPaymentInstructions($controlNumber, $amount)
    {
        return "Pay TSh " . number_format($amount) . " using control number: {$controlNumber}. " .
               "Send payment to Kilindi District Council via mobile money or bank transfer. " .
               "Reference: {$controlNumber}";
    }
}
