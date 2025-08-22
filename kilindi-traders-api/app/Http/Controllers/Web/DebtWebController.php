<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Debt;
use App\Models\Trader;
use Illuminate\Http\Request;

class DebtWebController extends Controller
{
    public function index()
    {
        $debts = Debt::with('trader')->whereIn('status', ['pending', 'overdue'])->latest()->paginate(10);
        
        $stats = [
            'total' => Debt::whereIn('status', ['pending', 'overdue'])->count(),
            'totalAmount' => Debt::whereIn('status', ['pending', 'overdue'])->sum('amount'),
            'pending' => Debt::where('status', 'pending')->count(),
            'pendingAmount' => Debt::where('status', 'pending')->sum('amount'),
            'paid' => Debt::where('status', 'paid')->count(),
            'paidAmount' => Debt::where('status', 'paid')->sum('amount'),
            'overdue' => Debt::where('status', 'overdue')->count(),
            'overdueAmount' => Debt::where('status', 'overdue')->sum('amount'),
        ];
        
        return view('debts.index', compact('debts', 'stats'));
    }

    public function create()
    {
        $traders = Trader::all();
        return view('debts.create', compact('traders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'trader_id' => 'required|exists:traders,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'due_date' => 'nullable|date',
        ]);

        // Set default status to pending
        $validated['status'] = 'pending';

        $debt = Debt::create($validated);

        // Generate control number
        $controlNumberService = app(\App\Services\ControlNumberService::class);
        $controlNumber = $controlNumberService->generateDebtControlNumber($debt);

        // Fire event to send debt notification SMS with control number
        event(new \App\Events\DebtCreated($debt));

        return redirect()->route('debts.index')
            ->with('success', 'Debt record created successfully! Control Number: ' . $controlNumber);
    }

    public function edit(Debt $debt)
    {
        $traders = Trader::all();
        return view('debts.edit', compact('debt', 'traders'));
    }

    public function update(Request $request, Debt $debt)
    {
        $validated = $request->validate([
            'trader_id' => 'required|exists:traders,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'due_date' => 'nullable|date',
            'status' => 'nullable|in:pending,paid,overdue',
        ]);

        // Only update provided fields
        $updateData = array_filter($validated, function($value) {
            return $value !== null;
        });
        
        $debt->update($updateData);

        return redirect()->route('debts.index')
            ->with('success', 'Debt record updated successfully!');
    }

    public function updateStatus(Request $request, Debt $debt)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,overdue',
        ]);

        $debt->update($validated);

        return redirect()->back()
            ->with('success', 'Debt status updated successfully!');
    }
}
