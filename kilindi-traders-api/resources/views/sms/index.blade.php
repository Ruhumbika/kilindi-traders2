@extends('layouts.app')

@section('title', 'Mawasiliano ya SMS')

@section('content')
<header class="border-b border-border bg-card px-6 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-6">SMS Messages</h1>
            <p class="text-muted-foreground">Tuma na fuatilia ujumbe wa SMS kwa wachuuzi</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('sms.bulk') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i data-lucide="users" class="h-4 w-4"></i>
                SMS za Wingi
            </a>
            <a href="{{ route('sms.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i data-lucide="plus" class="w-4 h-4 inline mr-2"></i>
                Send New SMS
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
                    <h3 class="text-sm font-medium">Total SMS</h3>
                    <i data-lucide="message-circle" class="h-4 w-4 text-muted-foreground"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold">{{ $stats['total'] }}</div>
                    <p class="text-xs text-muted-foreground">All time</p>
                </div>
            </div>

            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium">Sent</h3>
                    <i data-lucide="check-circle" class="h-4 w-4 text-green-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['sent'] }}</div>
                    <p class="text-xs text-muted-foreground">{{ $stats['successRate'] }}% success rate</p>
                </div>
            </div>

            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium">Failed</h3>
                    <i data-lucide="x-circle" class="h-4 w-4 text-red-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-red-600">{{ $stats['failed'] }}</div>
                    <p class="text-gray-500 text-center py-8">No SMS messages sent yet.</p>
                </div>
            </div>

            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium">This Month</h3>
                    <i data-lucide="calendar" class="h-4 w-4 text-blue-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['thisMonth'] }}</div>
                    <p class="text-xs text-muted-foreground">{{ $stats['pending'] }} pending</p>
                </div>
            </div>
        </div>

        <!-- SMS Logs Table -->
        <div class="bg-card border border-border rounded-lg shadow-sm">
            <div class="p-6 border-b border-border">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold">SMS History</h3>
                        <p class="text-sm text-muted-foreground">Track all SMS communications</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <select class="border border-border rounded px-3 py-1 text-sm" onchange="filterByStatus(this.value)">
                            <option value="all">All Status</option>
                            <option value="sent">Sent</option>
                            <option value="failed">Failed</option>
                            <option value="pending">Pending</option>
                        </select>
                        <select class="border border-border rounded px-3 py-1 text-sm" onchange="filterByType(this.value)">
                            <option value="all">All Types</option>
                            <option value="reminder">Reminder</option>
                            <option value="notification">Notification</option>
                            <option value="marketing">Marketing</option>
                            <option value="manual">Manual</option>
                        </select>
                        <div class="relative">
                            <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground h-4 w-4"></i>
                            <input type="text" placeholder="Search SMS..." 
                                   class="pl-10 w-64 border border-border rounded px-3 py-1 text-sm"
                                   onkeyup="searchSms(this.value)">
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="rounded-md border border-border overflow-hidden">
                    <table class="min-w-full divide-y divide-border">
                        <thead class="bg-muted/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trader</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-card divide-y divide-border" id="sms-table-body">
                            @forelse($smsLogs as $sms)
                                <tr class="sms-row" 
                                    data-status="{{ $sms->status }}" 
                                    data-type="{{ $sms->sms_type }}"
                                    data-search="{{ strtolower(($sms->trader->business_name ?? '') . ' ' . ($sms->trader->owner_name ?? '') . ' ' . $sms->phone_number . ' ' . $sms->message) }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $sms->phone_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($sms->trader)
                                            <div>
                                                <p class="font-medium">{{ $sms->trader->business_name }}</p>
                                                <p class="text-sm text-muted-foreground">{{ $sms->trader->owner_name }}</p>
                                            </div>
                                        @else
                                            <span class="text-muted-foreground">SMS ya Mwenyewe</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 max-w-xs">
                                        <div class="flex items-center gap-2">
                                            <p class="truncate" title="{{ $sms->message }}">{{ $sms->message }}</p>
                                            <a href="{{ route('sms.show', $sms) }}" 
                                               class="text-blue-500 hover:text-blue-700 flex items-center gap-1">
                                                <i data-lucide="eye" class="h-4 w-4"></i>
                                                <span class="text-xs">Angalia</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                            @if($sms->sms_type === 'reminder') bg-yellow-100 text-yellow-800
                                            @elseif($sms->sms_type === 'notification') bg-blue-100 text-blue-800
                                            @elseif($sms->sms_type === 'marketing') bg-purple-100 text-purple-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if($sms->sms_type === 'reminder')
                                                <i data-lucide="clock" class="h-3 w-3"></i>
                                            @elseif($sms->sms_type === 'notification')
                                                <i data-lucide="bell" class="h-3 w-3"></i>
                                            @elseif($sms->sms_type === 'marketing')
                                                <i data-lucide="megaphone" class="h-3 w-3"></i>
                                            @else
                                                <i data-lucide="message-circle" class="h-3 w-3"></i>
                                            @endif
                                            @if($sms->sms_type === 'reminder') Ukumbusho
                                            @elseif($sms->sms_type === 'notification') Taarifa
                                            @elseif($sms->sms_type === 'marketing') Uuzaji
                                            @elseif($sms->sms_type === 'payment_reminder') Payment Reminder
                                            @elseif($sms->sms_type === 'license_expiry') License Reminder
                                            @elseif($sms->sms_type === 'welcome') Welcome Message
                                            @else Mwenyewe @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                            @if($sms->status === 'sent') bg-green-100 text-green-800
                                            @elseif($sms->status === 'failed') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if($sms->status === 'sent')
                                                <i data-lucide="check-circle" class="h-3 w-3"></i>
                                            @elseif($sms->status === 'failed')
                                                <i data-lucide="x-circle" class="h-3 w-3"></i>
                                            @else
                                                <i data-lucide="clock" class="h-3 w-3"></i>
                                            @endif
                                            @if($sms->status === 'sent') Imetumwa
                                            @elseif($sms->status === 'failed') Imeshindwa
                                            @else Inasubiri @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-muted-foreground">
                                        {{ $sms->sent_at ? $sms->sent_at->format('d/m/Y H:i') : 'Haijatumwa' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-muted-foreground">
                                        Hakuna ujumbe wa SMS uliotumwa bado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($smsLogs->count() > 0)
                    <div class="flex items-center justify-between pt-4">
                        <p class="text-sm text-muted-foreground">
                            Showing <span id="showing-count">{{ $smsLogs->count() }}</span> of {{ $smsLogs->count() }} SMS messages
                        </p>
                        <div class="flex items-center gap-4 text-sm text-muted-foreground">
                            <span>Success Rate: <span class="font-medium">{{ $stats['successRate'] }}%</span></span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>

<script>
    function filterByStatus(status) {
        const rows = document.querySelectorAll('.sms-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowStatus = row.dataset.status;
            if (status === 'all' || rowStatus === status) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('showing-count').textContent = visibleCount;
    }

    function filterByType(type) {
        const rows = document.querySelectorAll('.sms-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowType = row.dataset.type;
            if (type === 'all' || rowType === type) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('showing-count').textContent = visibleCount;
    }

    function searchSms(searchTerm) {
        const rows = document.querySelectorAll('.sms-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const searchData = row.dataset.search;
            if (searchData.includes(searchTerm.toLowerCase())) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('showing-count').textContent = visibleCount;
    }
</script>
@endsection
