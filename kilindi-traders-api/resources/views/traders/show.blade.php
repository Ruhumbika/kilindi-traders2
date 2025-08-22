@extends('layouts.app')

@section('title', $trader->business_name)

@section('content')
<header class="border-b border-border bg-card px-6 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-card-foreground">{{ $trader->business_name }}</h1>
            <p class="text-muted-foreground">{{ $trader->owner_name }} • {{ $trader->phone_number }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('traders.edit', $trader) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i data-lucide="edit" class="h-4 w-4"></i>
                Edit Trader
            </a>
            <a href="{{ route('traders.index') }}" class="text-muted-foreground hover:text-foreground flex items-center gap-2">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Back
            </a>
        </div>
    </div>
</header>

<main class="flex-1 overflow-auto p-6">
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium">Total Debts</h3>
                    <i data-lucide="credit-card" class="h-4 w-4 text-red-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-red-600">TSh {{ number_format($stats['totalDebts']) }}</div>
                    <p class="text-xs text-muted-foreground">{{ $trader->debts->count() }} debts</p>
                </div>
            </div>

            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium">Paid Debts</h3>
                    <i data-lucide="check-circle" class="h-4 w-4 text-green-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-green-600">TSh {{ number_format($stats['paidDebts']) }}</div>
                    <p class="text-xs text-muted-foreground">{{ $trader->debts->where('status', 'paid')->count() }} paid</p>
                </div>
            </div>

            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium">Active Licenses</h3>
                    <i data-lucide="file-text" class="h-4 w-4 text-blue-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['activeLicenses'] }}</div>
                    <p class="text-xs text-muted-foreground">{{ $trader->licenses->count() }} total</p>
                </div>
            </div>

            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium">Total Payments</h3>
                    <i data-lucide="banknote" class="h-4 w-4 text-green-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-green-600">TSh {{ number_format($stats['totalPayments']) }}</div>
                    <p class="text-xs text-muted-foreground">{{ $trader->payments->count() }} payments</p>
                </div>
            </div>
        </div>

        <!-- Trader Details -->
        <div class="bg-card border border-border rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="font-semibold mb-4">Trader Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Business Name</label>
                            <p class="font-medium">{{ $trader->business_name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Owner Name</label>
                            <p class="font-medium">{{ $trader->owner_name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Business Type</label>
                            <p class="font-medium">{{ $trader->business_type }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Phone</label>
                            <p class="font-medium">{{ $trader->phone_number }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Email</label>
                            <p class="font-medium">{{ $trader->email ?: 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Business Location</label>
                            <p class="font-medium">{{ $trader->business_location ?: 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Control Number</label>
                            <p class="font-medium">{{ $trader->control_number ?: 'Not assigned' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Debts -->
            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="p-6 border-b border-border">
                    <h3 class="font-semibold">Recent Debts</h3>
                </div>
                <div class="p-6">
                    @forelse($trader->debts->take(5) as $debt)
                        <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-border' : '' }}">
                            <div>
                                <p class="font-medium">TSh {{ number_format($debt->amount) }}</p>
                                <p class="text-sm text-muted-foreground">{{ $debt->description ?: 'No description' }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($debt->status === 'paid') bg-green-100 text-green-800
                                @elseif($debt->status === 'overdue') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($debt->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted-foreground text-center py-4">No debts recorded</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="p-6 border-b border-border">
                    <h3 class="font-semibold">Recent Payments</h3>
                </div>
                <div class="p-6">
                    @forelse($trader->payments->take(5) as $payment)
                        <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-border' : '' }}">
                            <div>
                                <p class="font-medium text-green-600">+TSh {{ number_format($payment->amount) }}</p>
                                <p class="text-sm text-muted-foreground">{{ $payment->created_at->format('M d, Y') }}</p>
                            </div>
                            <span class="text-xs text-muted-foreground">{{ str_replace('_', ' ', ucfirst($payment->payment_method)) }}</span>
                        </div>
                    @empty
                        <p class="text-muted-foreground text-center py-4">No payments recorded</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
