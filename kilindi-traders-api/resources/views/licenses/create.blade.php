@extends('layouts.app')

@section('title', 'Issue New License')

@section('content')
<header class="border-b border-border bg-card px-6 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-card-foreground">Issue New License</h1>
            <p class="text-muted-foreground">Create a new trading license for a trader</p>
        </div>
        <a href="{{ route('licenses.index') }}" class="text-muted-foreground hover:text-foreground flex items-center gap-2">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Back to Licenses
        </a>
    </div>
</header>

<main class="flex-1 overflow-auto p-6">
    <div class="max-w-2xl mx-auto">
        <div class="bg-card border border-border rounded-lg shadow-sm">
            <div class="p-6">
                <form action="{{ route('licenses.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label for="trader_id" class="text-sm font-medium text-foreground">Trader *</label>
                        <select id="trader_id" name="trader_id" required 
                                class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select a trader</option>
                            @foreach($traders as $trader)
                                <option value="{{ $trader->id }}" {{ old('trader_id') == $trader->id ? 'selected' : '' }}>
                                    {{ $trader->business_name }} - {{ $trader->owner_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('trader_id')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="license_type" class="text-sm font-medium text-foreground">License Type *</label>
                        <select id="license_type" name="license_type" required onchange="updateFee()"
                                class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select license type</option>
                            <option value="Trading License" {{ old('license_type') == 'Trading License' ? 'selected' : '' }}>Trading License</option>
                            <option value="Business Permit" {{ old('license_type') == 'Business Permit' ? 'selected' : '' }}>Business Permit</option>
                            <option value="Market Stall License" {{ old('license_type') == 'Market Stall License' ? 'selected' : '' }}>Market Stall License</option>
                            <option value="Vendor Permit" {{ old('license_type') == 'Vendor Permit' ? 'selected' : '' }}>Vendor Permit</option>
                            <option value="Export License" {{ old('license_type') == 'Export License' ? 'selected' : '' }}>Export License</option>
                            <option value="Import License" {{ old('license_type') == 'Import License' ? 'selected' : '' }}>Import License</option>
                        </select>
                        @error('license_type')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="fee" class="text-sm font-medium text-foreground">License Fee (TSh) *</label>
                        <input type="number" id="fee" name="fee" required min="0" step="0.01"
                               value="{{ old('fee') }}"
                               placeholder="Enter license fee"
                               class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('fee')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="issue_date" class="text-sm font-medium text-foreground">Issue Date *</label>
                            <input type="date" id="issue_date" name="issue_date" required
                                   value="{{ old('issue_date', date('Y-m-d')) }}"
                                   class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('issue_date')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="expiry_date" class="text-sm font-medium text-foreground">Expiry Date *</label>
                            <input type="date" id="expiry_date" name="expiry_date" required
                                   value="{{ old('expiry_date') }}"
                                   class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('expiry_date')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <i data-lucide="info" class="h-5 w-5 text-blue-500 mt-0.5"></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">License Information</p>
                                <ul class="space-y-1 text-blue-700">
                                    <li>• All licenses are issued with "active" status by default</li>
                                    <li>• Expiry date must be after the issue date</li>
                                    <li>• License fees vary by type and district regulations</li>
                                    <li>• Renewal reminders are sent 30 days before expiry</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                        <a href="{{ route('licenses.index') }}" 
                           class="px-4 py-2 text-sm font-medium text-muted-foreground hover:text-foreground border border-border rounded-lg hover:bg-muted">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-lg flex items-center gap-2">
                            <i data-lucide="file-text" class="h-4 w-4"></i>
                            Issue License
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    // License fee suggestions based on type
    const licenseFees = {
        'Trading License': 50000,
        'Business Permit': 25000,
        'Market Stall License': 15000,
        'Vendor Permit': 10000,
        'Export License': 100000,
        'Import License': 75000
    };

    function updateFee() {
        const licenseType = document.getElementById('license_type').value;
        const feeInput = document.getElementById('fee');
        
        if (licenseFees[licenseType]) {
            feeInput.value = licenseFees[licenseType];
        }
    }

    // Set default expiry date to 1 year from issue date
    document.getElementById('issue_date').addEventListener('change', function() {
        const issueDate = new Date(this.value);
        if (issueDate) {
            const expiryDate = new Date(issueDate);
            expiryDate.setFullYear(expiryDate.getFullYear() + 1);
            document.getElementById('expiry_date').value = expiryDate.toISOString().split('T')[0];
        }
    });

    // Set minimum expiry date based on issue date
    document.getElementById('issue_date').addEventListener('change', function() {
        const issueDate = this.value;
        if (issueDate) {
            const nextDay = new Date(issueDate);
            nextDay.setDate(nextDay.getDate() + 1);
            document.getElementById('expiry_date').min = nextDay.toISOString().split('T')[0];
        }
    });

    // Initialize default dates
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        const issueInput = document.getElementById('issue_date');
        
        if (!issueInput.value) {
            issueInput.value = today;
            issueInput.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection
