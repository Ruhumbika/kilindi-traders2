<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\Trader;
use Illuminate\Http\Request;

class LicenseWebController extends Controller
{
    public function index()
    {
        $licenses = License::with('trader')->latest()->paginate(10);
        
        $stats = [
            'total' => License::count(),
            'totalValue' => License::sum('fee_amount'),
            'active' => License::where('status', 'active')->count(),
            'activeValue' => License::where('status', 'active')->sum('fee_amount'),
            'expired' => License::where('status', 'expired')->count(),
            'expiredValue' => License::where('status', 'expired')->sum('fee_amount'),
            'expiring' => License::where('expiry_date', '<=', now()->addDays(30))->where('status', 'active')->count(),
        ];
        
        return view('licenses.index', compact('licenses', 'stats'));
    }

    public function create()
    {
        $traders = Trader::all();
        return view('licenses.create', compact('traders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'trader_id' => 'required|exists:traders,id',
            'license_type' => 'required|string|max:100',
            'license_number' => 'nullable|string|max:100|unique:licenses',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'fee' => 'required|numeric|min:0',
        ]);

        // Generate license number if not provided
        if (empty($validated['license_number'])) {
            $validated['license_number'] = 'LIC-' . date('Y') . '-' . str_pad(License::count() + 1, 4, '0', STR_PAD_LEFT);
        }

        // Set default status to active
        $validated['status'] = 'active';
        
        // Map fee to fee_amount for database
        $validated['fee_amount'] = $validated['fee'];
        unset($validated['fee']);

        $license = License::create($validated);

        // Generate control number for license fee
        $controlNumberService = app(\App\Services\ControlNumberService::class);
        $controlNumber = $controlNumberService->generateLicenseControlNumber($license);

        return redirect()->route('licenses.index')
            ->with('success', 'License created successfully! Control Number: ' . $controlNumber);
    }

    public function edit(License $license)
    {
        $traders = Trader::all();
        return view('licenses.edit', compact('license', 'traders'));
    }

    public function update(Request $request, License $license)
    {
        $validated = $request->validate([
            'trader_id' => 'required|exists:traders,id',
            'license_type' => 'required|string|max:100',
            'license_number' => 'required|string|max:100|unique:licenses,license_number,' . $license->id,
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'status' => 'required|in:active,expired,suspended',
            'fee_amount' => 'required|numeric|min:0',
        ]);

        $license->update($validated);

        return redirect()->route('licenses.index')
            ->with('success', 'License updated successfully!');
    }

    public function renew(Request $request, License $license)
    {
        $validated = $request->validate([
            'expiry_date' => 'required|date|after:today',
            'fee_amount' => 'required|numeric|min:0',
        ]);

        $license->update([
            'expiry_date' => $validated['expiry_date'],
            'fee_amount' => $validated['fee_amount'],
            'status' => 'active',
        ]);

        return redirect()->route('licenses.index')
            ->with('success', 'License renewed successfully!');
    }
}
