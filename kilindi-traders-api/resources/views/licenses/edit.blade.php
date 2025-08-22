@extends('layouts.app')

@section('title', 'Edit License')

@section('content')
<header class="border-b border-border bg-card px-6 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-card-foreground">Edit License</h1>
            <p class="text-muted-foreground">Update license information</p>
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
                <form action="{{ route('licenses.update', $license) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-2">
                        <label for="trader_id" class="text-sm font-medium text-foreground">Trader *</label>
                        <select id="trader_id" name="trader_id" required 
                                class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select a trader</option>
                            @foreach($traders as $trader)
                                <option value="{{ $trader->id }}" {{ old('trader_id', $license->trader_id) == $trader->id ? 'selected' : '' }}>
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
                        <select id="license_type" name="license_type" required
                                class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select license type</option>
                            <option value="Trading License" {{ old('license_type', $license->license_type) == 'Trading License' ? 'selected' : '' }}>Trading License</option>
                            <option value="Business Permit" {{ old('license_type', $license->license_type) == 'Business Permit' ? 'selected' : '' }}>Business Permit</option>
                            <option value="Market Stall License" {{ old('license_type', $license->license_type) == 'Market Stall License' ? 'selected' : '' }}>Market Stall License</option>
                            <option value="Vendor Permit" {{ old('license_type', $license->license_type) == 'Vendor Permit' ? 'selected' : '' }}>Vendor Permit</option>
                            <option value="Export License" {{ old('license_type', $license->license_type) == 'Export License' ? 'selected' : '' }}>Export License</option>
                            <option value="Import License" {{ old('license_type', $license->license_type) == 'Import License' ? 'selected' : '' }}>Import License</option>
                        </select>
                        @error('license_type')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="fee" class="text-sm font-medium text-foreground">License Fee (TSh) *</label>
                        <input type="number" id="fee" name="fee" required min="0" step="0.01"
                               value="{{ old('fee', $license->fee) }}"
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
                                   value="{{ old('issue_date', $license->issue_date ? $license->issue_date->format('Y-m-d') : '') }}"
                                   class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('issue_date')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="expiry_date" class="text-sm font-medium text-foreground">Expiry Date *</label>
                            <input type="date" id="expiry_date" name="expiry_date" required
                                   value="{{ old('expiry_date', $license->expiry_date ? $license->expiry_date->format('Y-m-d') : '') }}"
                                   class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('expiry_date')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                        <a href="{{ route('licenses.index') }}" 
                           class="px-4 py-2 text-sm font-medium text-muted-foreground hover:text-foreground border border-border rounded-lg hover:bg-muted">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-lg flex items-center gap-2">
                            <i data-lucide="save" class="h-4 w-4"></i>
                            Update License
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
