@extends('layouts.app')

@section('title', 'Generated Report')

@section('content')
<header class="border-b border-border bg-card px-6 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-card-foreground">Generated Report</h1>
            <p class="text-muted-foreground">{{ ucfirst($validated['type']) }} report results</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('reports.index') }}" class="text-muted-foreground hover:text-foreground flex items-center gap-2">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Back to Reports
            </a>
            <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i data-lucide="printer" class="h-4 w-4"></i>
                Print Report
            </button>
        </div>
    </div>
</header>

<main class="flex-1 overflow-auto p-6">
    <div class="max-w-6xl mx-auto">
        <div class="bg-card border border-border rounded-lg shadow-sm">
            <div class="p-6">
                @if($validated['type'] === 'summary')
                    <h3 class="font-semibold mb-4">Summary Report</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-medium text-blue-800">Total Traders</h4>
                            <p class="text-2xl font-bold text-blue-600">{{ $reportData['traders'] }}</p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <h4 class="font-medium text-red-800">Active Debts</h4>
                            <p class="text-2xl font-bold text-red-600">{{ $reportData['debts'] }}</p>
                            <p class="text-sm text-red-700">TSh {{ number_format($reportData['total_debt_amount']) }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-medium text-green-800">Total Payments</h4>
                            <p class="text-2xl font-bold text-green-600">{{ $reportData['payments'] }}</p>
                            <p class="text-sm text-green-700">TSh {{ number_format($reportData['total_payment_amount']) }}</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h4 class="font-medium text-purple-800">Total Licenses</h4>
                            <p class="text-2xl font-bold text-purple-600">{{ $reportData['licenses'] }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-800">Paid Debts</h4>
                            <p class="text-2xl font-bold text-gray-600">{{ $reportData['paid_debts'] }}</p>
                            <p class="text-sm text-gray-700">TSh {{ number_format($reportData['paid_debt_amount']) }}</p>
                        </div>
                    </div>
                @else
                    <h3 class="font-semibold mb-4">{{ ucfirst($validated['type']) }} Report</h3>
                    @if($validated['date_from'] || $validated['date_to'])
                        <p class="text-sm text-muted-foreground mb-4">
                            Period: 
                            @if($validated['date_from']) From {{ date('M d, Y', strtotime($validated['date_from'])) }} @endif
                            @if($validated['date_to']) To {{ date('M d, Y', strtotime($validated['date_to'])) }} @endif
                        </p>
                    @endif
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-border">
                            <thead class="bg-muted/50">
                                @if($validated['type'] === 'traders')
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Business Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Owner</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Phone</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Location</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Type</th>
                                    </tr>
                                @elseif($validated['type'] === 'debts')
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Trader</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Due Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Description</th>
                                    </tr>
                                @elseif($validated['type'] === 'payments')
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Trader</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Method</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Reference</th>
                                    </tr>
                                @elseif($validated['type'] === 'licenses')
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Trader</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">License Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Number</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Issue Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Expiry Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Status</th>
                                    </tr>
                                @endif
                            </thead>
                            <tbody class="bg-card divide-y divide-border">
                                @forelse($reportData as $item)
                                    @if($validated['type'] === 'traders')
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->business_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->owner_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->phone_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->business_location }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->business_type }}</td>
                                        </tr>
                                    @elseif($validated['type'] === 'debts')
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->trader->business_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap font-mono">TSh {{ number_format($item->amount) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs rounded-full 
                                                    @if($item->status === 'paid') bg-green-100 text-green-800
                                                    @elseif($item->status === 'overdue') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->due_date ? date('M d, Y', strtotime($item->due_date)) : 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $item->description ?: 'N/A' }}</td>
                                        </tr>
                                    @elseif($validated['type'] === 'payments')
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->trader->business_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap font-mono text-green-600">+TSh {{ number_format($item->amount) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst(str_replace('_', ' ', $item->payment_method)) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ date('M d, Y', strtotime($item->payment_date)) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap font-mono">{{ $item->transaction_reference ?: 'N/A' }}</td>
                                        </tr>
                                    @elseif($validated['type'] === 'licenses')
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->trader->business_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->license_type }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap font-mono">{{ $item->license_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ date('M d, Y', strtotime($item->issue_date)) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ date('M d, Y', strtotime($item->expiry_date)) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs rounded-full 
                                                    @if($item->status === 'active') bg-green-100 text-green-800
                                                    @elseif($item->status === 'expired') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-8 text-muted-foreground">
                                            No data found for the selected criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
