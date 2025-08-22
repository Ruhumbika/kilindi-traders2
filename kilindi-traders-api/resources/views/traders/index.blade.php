@extends('layouts.app')

@section('title', 'Traders')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <header class="border-b border-border bg-card px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-card-foreground">Trader Management</h1>
                <p class="text-muted-foreground">Manage registered traders and business information</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="importExcel()" class="border border-border text-muted-foreground hover:text-foreground hover:bg-muted px-4 py-2 rounded-lg flex items-center gap-2">
                    <i data-lucide="upload" class="h-4 w-4"></i>
                    Import Excel
                </button>
                <button onclick="exportTraders()" class="border border-border text-muted-foreground hover:text-foreground hover:bg-muted px-4 py-2 rounded-lg flex items-center gap-2">
                    <i data-lucide="download" class="h-4 w-4"></i>
                    Export
                </button>
                <a href="{{ route('traders.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Add Trader
                </a>
            </div>
        </div>
        
        <!-- Filter Controls -->
        <div class="flex items-center justify-between mt-4 pt-4 border-t border-border">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <label for="per_page" class="text-sm font-medium text-muted-foreground">Show:</label>
                    <select id="per_page" onchange="changePerPage(this.value)" class="border border-border rounded-md px-3 py-1 text-sm bg-background">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 rows</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 rows</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 rows</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 rows</option>
                        <option value="200" {{ $perPage == 200 ? 'selected' : '' }}>200 rows</option>
                    </select>
                </div>
                <div class="text-sm text-muted-foreground">
                    Showing {{ $traders->firstItem() ?? 0 }} to {{ $traders->lastItem() ?? 0 }} of {{ $traders->total() }} traders
                </div>
            </div>
            <div class="flex items-center gap-2">
                <i data-lucide="users" class="h-4 w-4 text-muted-foreground"></i>
                <span class="text-sm font-medium">Total: {{ $traders->total() }} traders</span>
            </div>
        </div>
    </header>

    <!-- Traders Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto max-h-96 overflow-y-auto">
            <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0 z-10">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Business</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Control Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stats</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($traders as $trader)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $trader->business_name }}</div>
                                <div class="text-sm text-gray-500">{{ $trader->business_type ?? 'N/A' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $trader->owner_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $trader->phone_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $trader->control_number ?: 'Not assigned' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $trader->business_location ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex space-x-4">
                                <span class="text-red-600">{{ $trader->debts_count }} debts</span>
                                <span class="text-yellow-600">{{ $trader->licenses_count }} licenses</span>
                                <span class="text-green-600">{{ $trader->payments_count }} payments</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('traders.show', $trader) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                            <a href="{{ route('traders.edit', $trader) }}" class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                            <a href="#" class="text-red-600 hover:text-red-900">Delete</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            No traders found. <a href="{{ route('traders.create') }}" class="text-blue-600 hover:text-blue-900">Add the first trader</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
        
        <!-- Pagination -->
        @if($traders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing {{ $traders->firstItem() }} to {{ $traders->lastItem() }} of {{ $traders->total() }} results
                    </div>
                    <div class="flex items-center space-x-2">
                        {{-- Previous Page Link --}}
                        @if ($traders->onFirstPage())
                            <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $traders->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Previous</a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($traders->getUrlRange(1, $traders->lastPage()) as $page => $url)
                            @if ($page == $traders->currentPage())
                                <span class="px-3 py-2 text-sm text-white bg-blue-500 rounded-md">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($traders->hasMorePages())
                            <a href="{{ $traders->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Next</a>
                        @else
                            <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Next</span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
<script>
    function importExcel() {
        // Create hidden file input
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = '.xlsx,.xls,.csv';
        input.onchange = function(e) {
            const file = e.target.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('excel_file', file);
                
                fetch('{{ route("traders.import") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Import successful! ' + data.imported + ' traders imported.');
                        location.reload();
                    } else {
                        alert('Import failed: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Import error: ' + error.message);
                });
            }
        };
        input.click();
    }

    function exportTraders() {
        window.location.href = '/traders/export';
    }
    
    function changePerPage(perPage) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', perPage);
        url.searchParams.delete('page'); // Reset to first page when changing per_page
        window.location.href = url.toString();
    }
</script>
@endsection
