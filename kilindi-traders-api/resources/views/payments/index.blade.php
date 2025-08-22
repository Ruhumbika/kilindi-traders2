@extends('layouts.app')

@section('title', 'Payment Processing')

@section('content')
<header class="border-b border-border bg-card px-6 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-card-foreground">Payment Processing</h1>
            <p class="text-muted-foreground">Track all payments and transactions</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('payments.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Record Payment
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
                    <h3 class="text-sm font-medium">Total Payments</h3>
                    <i data-lucide="credit-card" class="h-4 w-4 text-muted-foreground"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold">{{ $stats['total'] }}</div>
                    <p class="text-xs text-muted-foreground">TSh {{ number_format($stats['totalAmount']) }}</p>
                </div>
            </div>

            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium">This Month</h3>
                    <i data-lucide="calendar" class="h-4 w-4 text-green-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['thisMonth'] }}</div>
                    <p class="text-xs text-muted-foreground">TSh {{ number_format($stats['thisMonthAmount']) }}</p>
                </div>
            </div>

            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium">Debt Payments</h3>
                    <i data-lucide="arrow-down-circle" class="h-4 w-4 text-blue-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['debtPayments'] }}</div>
                    <p class="text-xs text-muted-foreground">Debt collections</p>
                </div>
            </div>

            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium">License Payments</h3>
                    <i data-lucide="file-text" class="h-4 w-4 text-purple-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-purple-600">{{ $stats['licensePayments'] }}</div>
                    <p class="text-xs text-muted-foreground">License fees</p>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="bg-card border border-border rounded-lg shadow-sm">
            <div class="p-6 border-b border-border">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold">Payment History</h3>
                        <p class="text-sm text-muted-foreground">All recorded payments and transactions</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <select class="border border-border rounded px-3 py-1 text-sm" onchange="filterByMethod(this.value)">
                            <option value="all">All Methods</option>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="cheque">Cheque</option>
                        </select>
                        <div class="relative">
                            <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground h-4 w-4"></i>
                            <input type="text" placeholder="Search payments..." 
                                   class="pl-10 w-64 border border-border rounded px-3 py-1 text-sm"
                                   onkeyup="searchPayments(this.value)">
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="rounded-md border border-border overflow-hidden">
                    <table class="min-w-full divide-y divide-border">
                        <thead class="bg-muted/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Trader</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Reference</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider w-[70px]">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-card divide-y divide-border" id="payments-table-body">
                            @forelse($payments as $payment)
                                <tr class="payment-row" 
                                    data-method="{{ $payment->payment_method }}" 
                                    data-search="{{ strtolower($payment->trader->business_name . ' ' . $payment->trader->owner_name . ' ' . ($payment->transaction_reference ?? '') . ' ' . ($payment->notes ?? '')) }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <p class="font-medium">{{ $payment->trader->business_name ?? 'Unknown Business' }}</p>
                                            <p class="text-sm text-muted-foreground">{{ $payment->trader->owner_name }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-mono font-medium text-green-600">
                                        +TSh {{ number_format($payment->amount) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                            @if($payment->payment_method === 'cash') bg-green-100 text-green-800
                                            @elseif($payment->payment_method === 'bank_transfer') bg-blue-100 text-blue-800
                                            @elseif($payment->payment_method === 'mobile_money') bg-purple-100 text-purple-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if($payment->payment_method === 'cash')
                                                <i data-lucide="banknote" class="h-3 w-3"></i>
                                            @elseif($payment->payment_method === 'bank_transfer')
                                                <i data-lucide="building-2" class="h-3 w-3"></i>
                                            @elseif($payment->payment_method === 'mobile_money')
                                                <i data-lucide="smartphone" class="h-3 w-3"></i>
                                            @else
                                                <i data-lucide="file-text" class="h-3 w-3"></i>
                                            @endif
                                            {{ str_replace('_', ' ', ucfirst($payment->payment_method)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($payment->debt_id)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i data-lucide="credit-card" class="h-3 w-3"></i>
                                                Debt Payment
                                            </span>
                                        @elseif($payment->license_id)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i data-lucide="file-text" class="h-3 w-3"></i>
                                                License Fee
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i data-lucide="circle" class="h-3 w-3"></i>
                                                General
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono">
                                        {{ $payment->transaction_reference ?: 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-muted-foreground">
                                        {{ $payment->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="relative inline-block text-left">
                                            <button onclick="toggleDropdown('dropdown-{{ $payment->id }}')" class="text-gray-400 hover:text-gray-600 p-1">
                                                <i data-lucide="more-horizontal" class="h-4 w-4"></i>
                                            </button>
                                            <div id="dropdown-{{ $payment->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border">
                                                <div class="py-1">
                                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i data-lucide="eye" class="h-4 w-4 mr-2 inline"></i>View Details
                                                    </a>
                                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i data-lucide="download" class="h-4 w-4 mr-2 inline"></i>Download Receipt
                                                    </a>
                                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i data-lucide="edit" class="h-4 w-4 mr-2 inline"></i>Edit
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-8 text-muted-foreground">
                                        No payments recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($payments->count() > 0)
                    <div class="flex items-center justify-between pt-4">
                        <p class="text-sm text-muted-foreground">
                            Showing <span id="showing-count">{{ $payments->count() }}</span> of {{ $payments->count() }} payments
                        </p>
                        <div class="flex items-center gap-4 text-sm text-muted-foreground">
                            <span>Total: TSh <span id="total-amount">{{ number_format($stats['totalAmount']) }}</span></span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>

<script>
    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
        
        // Close all other dropdowns
        allDropdowns.forEach(d => {
            if (d.id !== id) d.classList.add('hidden');
        });
        
        // Toggle current dropdown
        dropdown.classList.toggle('hidden');
    }

    function filterByMethod(method) {
        const rows = document.querySelectorAll('.payment-row');
        let visibleCount = 0;
        let totalAmount = 0;

        rows.forEach(row => {
            const rowMethod = row.dataset.method;
            if (method === 'all' || rowMethod === method) {
                row.style.display = '';
                visibleCount++;
                const amountText = row.querySelector('td:nth-child(2)').textContent;
                const amount = parseFloat(amountText.replace(/[^\d.]/g, ''));
                totalAmount += amount;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('showing-count').textContent = visibleCount;
        document.getElementById('total-amount').textContent = totalAmount.toLocaleString();
    }

    function searchPayments(searchTerm) {
        const rows = document.querySelectorAll('.payment-row');
        let visibleCount = 0;
        let totalAmount = 0;

        rows.forEach(row => {
            const searchData = row.dataset.search;
            if (searchData.includes(searchTerm.toLowerCase())) {
                row.style.display = '';
                visibleCount++;
                const amountText = row.querySelector('td:nth-child(2)').textContent;
                const amount = parseFloat(amountText.replace(/[^\d.]/g, ''));
                totalAmount += amount;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('showing-count').textContent = visibleCount;
        document.getElementById('total-amount').textContent = totalAmount.toLocaleString();
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('[onclick*="toggleDropdown"]')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(d => d.classList.add('hidden'));
        }
    });
</script>
@endsection
