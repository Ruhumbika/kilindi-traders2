<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Trader;
use App\Models\Debt;
use App\Models\License;
use Illuminate\Http\Request;

class PaymentWebController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['trader', 'debt', 'license'])->latest()->paginate(10);
        
        $stats = [
            'total' => Payment::count(),
            'totalAmount' => Payment::sum('amount'),
            'thisMonth' => Payment::whereMonth('created_at', now()->month)->count(),
            'thisMonthAmount' => Payment::whereMonth('created_at', now()->month)->sum('amount'),
            'cash' => Payment::where('payment_method', 'cash')->count(),
            'mobile' => Payment::where('payment_method', 'mobile_money')->count(),
            'bank' => Payment::where('payment_method', 'bank_transfer')->count(),
            'cashAmount' => Payment::where('payment_method', 'cash')->sum('amount'),
            'mobileAmount' => Payment::where('payment_method', 'mobile_money')->sum('amount'),
            'bankAmount' => Payment::where('payment_method', 'bank_transfer')->sum('amount'),
            'debtPayments' => Payment::whereNotNull('debt_id')->count(),
            'debtPaymentsAmount' => Payment::whereNotNull('debt_id')->sum('amount'),
            'licensePayments' => Payment::whereNotNull('license_id')->count(),
        ];
        
        return view('payments.index', compact('payments', 'stats'));
    }

    public function create()
    {
        $traders = Trader::all();
        $debts = Debt::where('status', '!=', 'paid')->with('trader')->get();
        $licenses = License::where('status', 'active')->with('trader')->get();
        
        return view('payments.create', compact('traders', 'debts', 'licenses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'trader_id' => 'required|exists:traders,id',
            'debt_id' => 'nullable|exists:debts,id',
            'license_id' => 'nullable|exists:licenses,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_money,cheque',
            'transaction_reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        // Set payment date to current date
        $validated['payment_date'] = now();

        $payment = Payment::create($validated);

        // Use PaymentService to process payment and update statuses
        $paymentService = app(\App\Services\PaymentService::class);
        $result = $paymentService->processPayment($payment);

        // Fire event to send payment confirmation SMS
        event(new \App\Events\PaymentMade($payment));

        return redirect()->route('payments.index')
            ->with('success', $result['message']);
    }
}
