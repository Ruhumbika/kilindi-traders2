<?php

namespace App\Services;

use App\Models\Debt;
use App\Models\Payment;
use App\Models\License;

class PaymentService
{
    public function processPayment(Payment $payment)
    {
        $result = ['status' => 'success', 'message' => 'Payment recorded successfully!'];

        // Handle debt payment
        if ($payment->debt_id) {
            $result = $this->processDebtPayment($payment);
        }

        // Handle license payment
        if ($payment->license_id) {
            $result = $this->processLicensePayment($payment);
        }

        return $result;
    }

    private function processDebtPayment(Payment $payment)
    {
        $debt = Debt::find($payment->debt_id);
        $totalPaid = Payment::where('debt_id', $debt->id)->sum('amount');
        
        if ($totalPaid >= $debt->amount) {
            $debt->update(['status' => 'paid']);
            return [
                'status' => 'paid',
                'message' => 'Payment recorded successfully! Debt has been marked as PAID.',
                'debt_status' => 'paid'
            ];
        } else {
            $remainingAmount = $debt->amount - $totalPaid;
            return [
                'status' => 'partial',
                'message' => 'Payment recorded successfully! Remaining debt: TSh ' . number_format($remainingAmount),
                'remaining_amount' => $remainingAmount,
                'debt_status' => 'pending'
            ];
        }
    }

    private function processLicensePayment(Payment $payment)
    {
        $license = License::find($payment->license_id);
        $totalPaid = Payment::where('license_id', $license->id)->sum('amount');
        
        if ($totalPaid >= $license->fee_amount) {
            $license->update(['status' => 'active']);
            return [
                'status' => 'paid',
                'message' => 'License fee payment recorded successfully! License is now ACTIVE.',
                'license_status' => 'active'
            ];
        } else {
            $remainingAmount = $license->fee_amount - $totalPaid;
            return [
                'status' => 'partial',
                'message' => 'Partial license fee payment recorded! Remaining: TSh ' . number_format($remainingAmount),
                'remaining_amount' => $remainingAmount,
                'license_status' => 'pending'
            ];
        }
    }

    public function findPaymentByControlNumber($controlNumber)
    {
        // Find debt by control number
        $debt = Debt::where('control_number', $controlNumber)->first();
        if ($debt) {
            return ['type' => 'debt', 'record' => $debt];
        }

        // Find license by control number
        $license = License::where('control_number', $controlNumber)->first();
        if ($license) {
            return ['type' => 'license', 'record' => $license];
        }

        return null;
    }

    public function getPaymentHistory($traderId)
    {
        return Payment::where('trader_id', $traderId)
            ->with(['debt', 'license'])
            ->orderBy('payment_date', 'desc')
            ->get();
    }

    public function getOutstandingDebts($traderId)
    {
        return Debt::where('trader_id', $traderId)
            ->whereIn('status', ['pending', 'overdue'])
            ->get();
    }

    public function getPendingLicenses($traderId)
    {
        return License::where('trader_id', $traderId)
            ->where('status', 'pending')
            ->get();
    }
}
