@extends('layouts.app')

@section('title', 'License Management')

@section('content')
<header class="border-b border-border bg-card px-6 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-card-foreground">License Management</h1>
            <p class="text-muted-foreground">Track trader licenses, renewals, and compliance</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('licenses.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Issue License
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
                    <h3 class="text-sm font-medium">Total Licenses</h3>
                    <i data-lucide="file-text" class="h-4 w-4 text-muted-foreground"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold">{{ $stats['total'] }}</div>
                    <p class="text-xs text-muted-foreground">TSh {{ number_format($stats['totalValue']) }} total value</p>
                </div>
            </div>

            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium">Active</h3>
                    <i data-lucide="check-circle" class="h-4 w-4 text-green-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</div>
                    <p class="text-xs text-muted-foreground">TSh {{ number_format($stats['activeValue']) }}</p>
                </div>
            </div>

            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium">Expired</h3>
                    <i data-lucide="x-circle" class="h-4 w-4 text-red-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-red-600">{{ $stats['expired'] }}</div>
                    <p class="text-xs text-muted-foreground">TSh {{ number_format($stats['expiredValue']) }}</p>
                </div>
            </div>

            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium">Expiring Soon</h3>
                    <i data-lucide="alert-triangle" class="h-4 w-4 text-amber-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-amber-600">{{ $stats['expiring'] }}</div>
                    <p class="text-xs text-muted-foreground">Within 30 days</p>
                </div>
            </div>
        </div>

        <!-- Licenses Table -->
        <div class="bg-card border border-border rounded-lg shadow-sm">
            <div class="p-6 border-b border-border">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold">License Registry</h3>
                        <p class="text-sm text-muted-foreground">Track all trader licenses and their status</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <select class="border border-border rounded px-3 py-1 text-sm" onchange="filterByStatus(this.value)">
                            <option value="all">All Status</option>
                            <option value="active">Active</option>
                            <option value="expired">Expired</option>
                            <option value="suspended">Suspended</option>
                        </select>
                        <div class="relative">
                            <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground h-4 w-4"></i>
                            <input type="text" placeholder="Search licenses..." 
                                   class="pl-10 w-64 border border-border rounded px-3 py-1 text-sm"
                                   onkeyup="searchLicenses(this.value)">
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">License Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Fee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Issue Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Expiry Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider w-[70px]">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-card divide-y divide-border" id="licenses-table-body">
                            @forelse($licenses as $license)
                                @php
                                    $isExpiring = $license->expiry_date && $license->expiry_date->diffInDays(now()) <= 30 && $license->status === 'active';
                                    $isExpired = $license->status === 'expired';
                                @endphp
                                <tr class="license-row {{ $isExpiring ? 'bg-amber-50' : ($isExpired ? 'bg-red-50' : '') }}" 
                                    data-status="{{ $license->status }}" 
                                    data-search="{{ strtolower($license->trader->business_name . ' ' . $license->trader->owner_name . ' ' . $license->license_type) }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <p class="font-medium">{{ $license->trader->business_name ?? 'Unknown Business' }}</p>
                                            <p class="text-sm text-muted-foreground">{{ $license->trader->owner_name }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-medium">{{ $license->license_type }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-mono font-medium">
                                        TSh {{ number_format($license->fee) }}
                                        @if($license->penalty > 0)
                                            <div class="text-xs text-red-600">+{{ number_format($license->penalty) }} penalty</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $license->issue_date ? $license->issue_date->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($license->expiry_date)
                                            <div class="text-sm {{ $isExpiring ? 'text-amber-600 font-medium' : ($isExpired ? 'text-red-600 font-medium' : '') }}">
                                                {{ $license->expiry_date->format('M d, Y') }}
                                                @if($isExpiring)
                                                    <div class="text-xs text-amber-500">Expires in {{ $license->expiry_date->diffInDays(now()) }} days</div>
                                                @elseif($isExpired)
                                                    <div class="text-xs text-red-500">Expired {{ $license->expiry_date->diffForHumans() }}</div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted-foreground">No expiry</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                            @if($license->status === 'active') bg-green-100 text-green-800
                                            @elseif($license->status === 'expired') bg-red-100 text-red-800
                                            @elseif($license->status === 'suspended') bg-gray-100 text-gray-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if($license->status === 'active')
                                                <i data-lucide="check-circle" class="h-3 w-3"></i>
                                            @elseif($license->status === 'expired')
                                                <i data-lucide="x-circle" class="h-3 w-3"></i>
                                            @elseif($license->status === 'suspended')
                                                <i data-lucide="pause-circle" class="h-3 w-3"></i>
                                            @else
                                                <i data-lucide="circle" class="h-3 w-3"></i>
                                            @endif
                                            {{ ucfirst($license->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="relative inline-block text-left">
                                            <button onclick="toggleDropdown('dropdown-{{ $license->id }}')" class="text-gray-400 hover:text-gray-600 p-1">
                                                <i data-lucide="more-horizontal" class="h-4 w-4"></i>
                                            </button>
                                            <div id="dropdown-{{ $license->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border">
                                                <div class="py-1">
                                                    <a href="{{ route('licenses.edit', $license) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i data-lucide="edit" class="h-4 w-4 mr-2 inline"></i>Edit
                                                    </a>
                                                    @if($license->status === 'expired' || $isExpiring)
                                                        <button onclick="openRenewalModal({{ $license->id }}, '{{ $license->license_type }}', {{ $license->fee }})" 
                                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <i data-lucide="refresh-cw" class="h-4 w-4 mr-2 inline"></i>Renew License
                                                        </button>
                                                    @endif
                                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i data-lucide="download" class="h-4 w-4 mr-2 inline"></i>Download Certificate
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-8 text-muted-foreground">
                                        No licenses issued yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($licenses->count() > 0)
                    <div class="flex items-center justify-between pt-4">
                        <p class="text-sm text-muted-foreground">
                            Showing <span id="showing-count">{{ $licenses->count() }}</span> of {{ $licenses->count() }} licenses
                        </p>
                        <div class="flex items-center gap-4 text-sm text-muted-foreground">
                            <span>Total Value: TSh <span id="total-value">{{ number_format($stats['totalValue']) }}</span></span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>

<!-- Renewal Modal -->
<div id="renewal-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Renew License</h3>
                    <button onclick="closeRenewalModal()" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>
                
                <form id="renewal-form" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">License Type</label>
                        <input type="text" id="renewal-license-type" readonly 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-50">
                    </div>
                    
                    <div>
                        <label for="renewal-fee" class="block text-sm font-medium text-gray-700 mb-1">Renewal Fee (TSh) *</label>
                        <input type="number" id="renewal-fee" name="fee" required min="0" step="0.01"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="renewal-expiry" class="block text-sm font-medium text-gray-700 mb-1">New Expiry Date *</label>
                        <input type="date" id="renewal-expiry" name="expiry_date" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="flex items-center justify-end gap-3 pt-4">
                        <button type="button" onclick="closeRenewalModal()" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-lg">
                            Renew License
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
        const rows = document.querySelectorAll('.license-row');
        let visibleCount = 0;
        let totalValue = 0;

        rows.forEach(row => {
            const rowStatus = row.dataset.status;
            if (status === 'all' || rowStatus === status) {
                row.style.display = '';
                visibleCount++;
                const feeText = row.querySelector('td:nth-child(3)').textContent;
                const fee = parseFloat(feeText.replace(/[^\d.]/g, ''));
                totalValue += fee;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('showing-count').textContent = visibleCount;
        document.getElementById('total-value').textContent = totalValue.toLocaleString();
    }

    function searchLicenses(searchTerm) {
        const rows = document.querySelectorAll('.license-row');
        let visibleCount = 0;
        let totalValue = 0;

        rows.forEach(row => {
            const searchData = row.dataset.search;
            if (searchData.includes(searchTerm.toLowerCase())) {
                row.style.display = '';
                visibleCount++;
                const feeText = row.querySelector('td:nth-child(3)').textContent;
                const fee = parseFloat(feeText.replace(/[^\d.]/g, ''));
                totalValue += fee;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('showing-count').textContent = visibleCount;
        document.getElementById('total-value').textContent = totalValue.toLocaleString();
    }

    function openRenewalModal(licenseId, licenseType, currentFee) {
        document.getElementById('renewal-license-type').value = licenseType;
        document.getElementById('renewal-fee').value = currentFee;
        
        // Set minimum date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('renewal-expiry').min = tomorrow.toISOString().split('T')[0];
        
        // Set form action
        document.getElementById('renewal-form').action = `/licenses/${licenseId}/renew`;
        
        document.getElementById('renewal-modal').classList.remove('hidden');
    }

    function closeRenewalModal() {
        document.getElementById('renewal-modal').classList.add('hidden');
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('[onclick*="toggleDropdown"]')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(d => d.classList.add('hidden'));
        }
    });

    // Close modal when clicking outside
    document.getElementById('renewal-modal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeRenewalModal();
        }
    });
</script>
@endsection
