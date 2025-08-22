@extends('layouts.app')

@section('title', 'Debt Collection')

@section('content')
<header class="border-b border-border bg-card px-6 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-card-foreground">Debt Collection</h1>
            <p class="text-muted-foreground">Track and manage trader debts and payments</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('debts.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Add Debt
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
                    <i data-lucide="credit-card" class="h-4 w-4 text-muted-foreground"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold">{{ $stats['total'] }}</div>
                    <p class="text-xs text-muted-foreground">TSh {{ number_format($stats['totalAmount']) }}</p>
                </div>
            </div>

            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium">Pending</h3>
                    <i data-lucide="clock" class="h-4 w-4 text-amber-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</div>
                    <p class="text-xs text-muted-foreground">TSh {{ number_format($stats['pendingAmount']) }}</p>
                </div>
            </div>

            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium">Paid</h3>
                    <i data-lucide="check-circle" class="h-4 w-4 text-green-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['paid'] }}</div>
                    <p class="text-xs text-muted-foreground">TSh {{ number_format($stats['paidAmount']) }}</p>
                </div>
            </div>

            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium">Overdue</h3>
                    <i data-lucide="alert-triangle" class="h-4 w-4 text-red-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-red-600">{{ $stats['overdue'] }}</div>
                    <p class="text-xs text-muted-foreground">TSh {{ number_format($stats['overdueAmount']) }}</p>
                </div>
            </div>
        </div>

        <!-- Debts Table -->
        <div class="bg-card border border-border rounded-lg shadow-sm">
            <div class="p-6 border-b border-border">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold">Debt Collection</h3>
                        <p class="text-sm text-muted-foreground">Manage trader debts and payment tracking</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <select class="border border-border rounded px-3 py-1 text-sm" onchange="filterByStatus(this.value)">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="overdue">Overdue</option>
                        </select>
                        <div class="relative">
                            <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground h-4 w-4"></i>
                            <input type="text" placeholder="Search debts..." 
                                   class="pl-10 w-64 border border-border rounded px-3 py-1 text-sm"
                                   onkeyup="searchDebts(this.value)">
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Due Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Created</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider w-[70px]">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-card divide-y divide-border" id="debts-table-body">
                            @forelse($debts as $debt)
                                @php
                                    $isOverdue = $debt->due_date && $debt->due_date->isPast() && $debt->status === 'pending';
                                @endphp
                                <tr class="debt-row {{ $isOverdue ? 'bg-red-50' : '' }}" data-status="{{ $debt->status }}" data-search="{{ strtolower($debt->trader->business_name . ' ' . $debt->trader->owner_name . ' ' . ($debt->description ?? '')) }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <p class="font-medium">{{ $debt->trader->business_name ?? 'Unknown Business' }}</p>
                                            <p class="text-sm text-muted-foreground">{{ $debt->trader->owner_name }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-mono font-medium">TSh {{ number_format($debt->amount) }}</td>
                                    <td class="px-6 py-4 max-w-xs">
                                        <p class="truncate" title="{{ $debt->description }}">{{ $debt->description ?: 'No description' }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($debt->due_date)
                                            <div class="text-sm {{ $isOverdue ? 'text-red-600 font-medium' : '' }}">
                                                {{ $debt->due_date->format('M d, Y') }}
                                                @if($isOverdue)
                                                    <div class="text-xs text-red-500">Overdue</div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted-foreground">No due date</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                            @if($debt->status === 'paid') bg-green-100 text-green-800
                                            @elseif($debt->status === 'overdue') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if($debt->status === 'paid')
                                                <i data-lucide="check-circle" class="h-3 w-3"></i>
                                            @elseif($debt->status === 'overdue')
                                                <i data-lucide="alert-triangle" class="h-3 w-3"></i>
                                            @else
                                                <i data-lucide="clock" class="h-3 w-3"></i>
                                            @endif
                                            {{ ucfirst($debt->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-muted-foreground">
                                        {{ $debt->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="relative inline-block text-left">
                                            <button onclick="toggleDropdown('dropdown-{{ $debt->id }}')" class="text-gray-400 hover:text-gray-600 p-1">
                                                <i data-lucide="more-horizontal" class="h-4 w-4"></i>
                                            </button>
                                            <div id="dropdown-{{ $debt->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border">
                                                <div class="py-1">
                                                    <a href="{{ route('debts.edit', $debt) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i data-lucide="edit" class="h-4 w-4 mr-2 inline"></i>Edit
                                                    </a>
                                                    @if($debt->status !== 'paid')
                                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <i data-lucide="credit-card" class="h-4 w-4 mr-2 inline"></i>Record Payment
                                                        </a>
                                                    @endif
                                                    @if($debt->status === 'pending' && $isOverdue)
                                                        <form action="{{ route('debts.update-status', $debt) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="overdue">
                                                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                <i data-lucide="alert-triangle" class="h-4 w-4 mr-2 inline"></i>Mark Overdue
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if($debt->status === 'overdue')
                                                        <form action="{{ route('debts.update-status', $debt) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="pending">
                                                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                <i data-lucide="clock" class="h-4 w-4 mr-2 inline"></i>Mark Pending
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-8 text-muted-foreground">
                                        No debts recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($debts->count() > 0)
                    <div class="flex items-center justify-between pt-4">
                        <p class="text-sm text-muted-foreground">
                            Showing <span id="showing-count">{{ $debts->count() }}</span> of {{ $debts->count() }} debts
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

    function filterByStatus(status) {
        const rows = document.querySelectorAll('.debt-row');
        let visibleCount = 0;
        let totalAmount = 0;

        rows.forEach(row => {
            const rowStatus = row.dataset.status;
            if (status === 'all' || rowStatus === status) {
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

    function searchDebts(searchTerm) {
        const rows = document.querySelectorAll('.debt-row');
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
