<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Trader;
use Illuminate\Http\Request;

class TraderWebController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $allowedPerPage = [10, 25, 50, 100, 200];
        
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }
        
        $traders = Trader::withCount(['debts', 'licenses', 'payments'])
            ->latest()
            ->paginate($perPage)
            ->appends($request->query());
            
        return view('traders.index', compact('traders', 'perPage'));
    }

    public function create()
    {
        return view('traders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'business_location' => 'required|string|max:255',
            'business_type' => 'required|string|max:100',
        ]);

        $trader = Trader::create($validated);

        // Fire event to send welcome SMS
        event(new \App\Events\TraderRegistered($trader));

        return redirect()->route('traders.index')
            ->with('success', 'Trader created successfully!');
    }

    public function show(Trader $trader)
    {
        $trader->load(['debts', 'licenses', 'payments']);
        
        $stats = [
            'totalDebts' => $trader->debts->sum('amount'),
            'totalPayments' => $trader->payments->sum('amount'),
            'paidDebts' => $trader->debts->where('status', 'paid')->sum('amount'),
            'pendingDebts' => $trader->debts->where('status', 'pending')->sum('amount'),
            'overdueDebts' => $trader->debts->where('status', 'overdue')->sum('amount'),
            'activeLicenses' => $trader->licenses->where('expiry_date', '>', now())->count(),
        ];
        
        return view('traders.show', compact('trader', 'stats'));
    }

    public function edit(Trader $trader)
    {
        return view('traders.edit', compact('trader'));
    }

    public function update(Request $request, Trader $trader)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|unique:traders,phone_number,' . $trader->id,
            'business_location' => 'nullable|string|max:255',
            'business_type' => 'required|string|max:100',
            'license_number' => 'nullable|string|max:100|unique:traders,license_number,' . $trader->id,
        ]);

        $trader->update($validated);

        return redirect()->route('traders.show', $trader)
            ->with('success', 'Trader updated successfully!');
    }

    public function export()
    {
        $traders = Trader::all();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="traders_export.csv"',
        ];

        $callback = function() use ($traders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Business Name', 'Owner Name', 'Phone Number', 'Business Location', 'Business Type', 'License Number']);
            
            foreach ($traders as $trader) {
                fputcsv($file, [
                    $trader->business_name,
                    $trader->owner_name,
                    $trader->phone_number,
                    $trader->business_location,
                    $trader->business_type,
                    $trader->license_number,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $header = array_shift($data);

        foreach ($data as $row) {
            $trader = array_combine($header, $row);
            
            Trader::create([
                'business_name' => $trader['Business Name'] ?? '',
                'owner_name' => $trader['Owner Name'] ?? '',
                'phone_number' => $trader['Phone Number'] ?? '',
                'business_location' => $trader['Business Location'] ?? '',
                'business_type' => $trader['Business Type'] ?? '',
                'license_number' => $trader['License Number'] ?? '',
            ]);
        }

        return redirect()->route('traders.index')
            ->with('success', 'Traders imported successfully!');
    }
}
