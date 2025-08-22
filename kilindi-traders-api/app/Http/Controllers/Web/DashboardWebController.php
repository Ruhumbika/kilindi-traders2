<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Trader;
use App\Models\Debt;
use App\Models\License;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardWebController extends Controller
{
    public function index()
    {
        $stats = [
            'total_traders' => Trader::count(),
            'total_debts' => Debt::whereIn('status', ['pending', 'overdue'])->count(),
            'pending_debts' => Debt::where('status', 'pending')->count(),
            'pending_debt_amount' => Debt::where('status', 'pending')->sum('amount'),
            'overdue_debts' => Debt::where('status', 'overdue')->count(),
            'overdue_debt_amount' => Debt::where('status', 'overdue')->sum('amount'),
            'paid_debt_amount' => Debt::where('status', 'paid')->sum('amount'),
            'active_debt_amount' => Debt::whereIn('status', ['pending', 'overdue'])->sum('amount'),
            'total_licenses' => License::count(),
            'expired_licenses' => License::where('expiry_date', '<', now())->count(),
            'expiring_licenses' => License::whereBetween('expiry_date', [now(), now()->addDays(30)])->count(),
            'total_payments' => Payment::count(),
            'total_payment_amount' => Payment::sum('amount'),
            'total_revenue' => Payment::sum('amount'),
        ];

        $recent_traders = Trader::latest()->take(5)->get();
        $recent_debts = Debt::with('trader')->whereIn('status', ['pending', 'overdue'])->latest()->take(5)->get();
        $recent_payments = Payment::with('trader')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recent_traders', 'recent_debts', 'recent_payments'));
    }
}
