<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Trader;
use App\Models\Debt;
use App\Models\License;
use App\Models\Payment;
use Illuminate\Http\Request;

class ReportWebController extends Controller
{
    public function index()
    {
        $stats = [
            'total_traders' => Trader::count(),
            'total_debts' => Debt::sum('amount'),
            'total_payments' => Payment::sum('amount'),
            'total_licenses' => License::count(),
        ];
        
        return view('reports.index', compact('stats'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:summary,traders,debts,payments,licenses',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        // Map type to report_type for consistency
        $validated['report_type'] = $validated['type'];

        $reportData = [];
        
        switch ($validated['report_type']) {
            case 'summary':
                $reportData = [
                    'traders' => Trader::count(),
                    'debts' => Debt::whereIn('status', ['pending', 'overdue'])->count(),
                    'payments' => Payment::count(),
                    'licenses' => License::count(),
                    'total_debt_amount' => Debt::whereIn('status', ['pending', 'overdue'])->sum('amount'),
                    'total_payment_amount' => Payment::sum('amount'),
                    'paid_debts' => Debt::where('status', 'paid')->count(),
                    'paid_debt_amount' => Debt::where('status', 'paid')->sum('amount'),
                ];
                break;
                
            case 'traders':
                $reportData = Trader::when($validated['date_from'], function ($query) use ($validated) {
                    return $query->whereDate('created_at', '>=', $validated['date_from']);
                })->when($validated['date_to'], function ($query) use ($validated) {
                    return $query->whereDate('created_at', '<=', $validated['date_to']);
                })->get();
                break;
                
            case 'debts':
                $reportData = Debt::with('trader')->whereIn('status', ['pending', 'overdue'])->when($validated['date_from'], function ($query) use ($validated) {
                    return $query->whereDate('created_at', '>=', $validated['date_from']);
                })->when($validated['date_to'], function ($query) use ($validated) {
                    return $query->whereDate('created_at', '<=', $validated['date_to']);
                })->get();
                break;
                
            case 'payments':
                $reportData = Payment::with('trader')->when($validated['date_from'], function ($query) use ($validated) {
                    return $query->whereDate('payment_date', '>=', $validated['date_from']);
                })->when($validated['date_to'], function ($query) use ($validated) {
                    return $query->whereDate('payment_date', '<=', $validated['date_to']);
                })->get();
                break;
                
            case 'licenses':
                $reportData = License::with('trader')->when($validated['date_from'], function ($query) use ($validated) {
                    return $query->whereDate('issue_date', '>=', $validated['date_from']);
                })->when($validated['date_to'], function ($query) use ($validated) {
                    return $query->whereDate('issue_date', '<=', $validated['date_to']);
                })->get();
                break;
        }

        return view('reports.generate', compact('reportData', 'validated'));
    }
}
