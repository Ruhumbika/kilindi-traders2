@extends('layouts.app')

@section('title', 'Edit Trader')

@section('content')
<header class="border-b border-border bg-card px-6 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-card-foreground">Edit Trader</h1>
            <p class="text-muted-foreground">Update trader information</p>
        </div>
        <a href="{{ route('traders.show', $trader) }}" class="text-muted-foreground hover:text-foreground flex items-center gap-2">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Back to Trader
        </a>
    </div>
</header>

<main class="flex-1 overflow-auto p-6">
    <div class="max-w-2xl mx-auto">
        <div class="bg-card border border-border rounded-lg shadow-sm">
            <div class="p-6">
                <form action="{{ route('traders.update', $trader) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="business_name" class="text-sm font-medium text-foreground">Business Name *</label>
                            <input type="text" id="business_name" name="business_name" required
                                   value="{{ old('business_name', $trader->business_name) }}"
                                   placeholder="Enter business name"
                                   class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('business_name')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="owner_name" class="text-sm font-medium text-foreground">Owner Name *</label>
                            <input type="text" id="owner_name" name="owner_name" required
                                   value="{{ old('owner_name', $trader->owner_name) }}"
                                   placeholder="Enter owner's full name"
                                   class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('owner_name')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="phone" class="text-sm font-medium text-foreground">Phone Number *</label>
                            <input type="tel" id="phone_number" name="phone_number" required
                                   value="{{ old('phone_number', $trader->phone_number) }}"
                                   placeholder="+255 XXX XXX XXX"
                                   class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('phone_number')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="email" class="text-sm font-medium text-foreground">Email</label>
                            <input type="email" id="email" name="email"
                                   value="{{ old('email', $trader->email) }}"
                                   placeholder="Enter email address"
                                   class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('email')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="control_number" class="text-sm font-medium text-foreground">Control Number</label>
                            <input type="text" id="control_number" name="control_number"
                                   value="{{ old('control_number', $trader->control_number) }}"
                                   placeholder="e.g., KLD000001"
                                   class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('control_number')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="license_number" class="text-sm font-medium text-foreground">License Number</label>
                            <input type="text" id="license_number" name="license_number"
                                   value="{{ old('license_number', $trader->license_number) }}"
                                   placeholder="Enter license number"
                                   class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('license_number')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="business_type" class="text-sm font-medium text-foreground">Business Type *</label>
                        <select id="business_type" name="business_type" required
                                class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select business type</option>
                            <option value="retail" {{ old('business_type', $trader->business_type) == 'retail' ? 'selected' : '' }}>Retail Shop</option>
                            <option value="wholesale" {{ old('business_type', $trader->business_type) == 'wholesale' ? 'selected' : '' }}>Wholesale</option>
                            <option value="restaurant" {{ old('business_type', $trader->business_type) == 'restaurant' ? 'selected' : '' }}>Restaurant/Food Service</option>
                            <option value="services" {{ old('business_type', $trader->business_type) == 'services' ? 'selected' : '' }}>Services</option>
                            <option value="manufacturing" {{ old('business_type', $trader->business_type) == 'manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                            <option value="agriculture" {{ old('business_type', $trader->business_type) == 'agriculture' ? 'selected' : '' }}>Agriculture</option>
                            <option value="other" {{ old('business_type', $trader->business_type) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('business_type')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="business_location" class="text-sm font-medium text-foreground">Business Location</label>
                        <textarea id="business_location" name="business_location" rows="3"
                                  placeholder="Enter business location"
                                  class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('business_location', $trader->business_location) }}</textarea>
                        @error('business_location')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                        <a href="{{ route('traders.show', $trader) }}" 
                           class="px-4 py-2 text-sm font-medium text-muted-foreground hover:text-foreground border border-border rounded-lg hover:bg-muted">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-lg flex items-center gap-2">
                            <i data-lucide="save" class="h-4 w-4"></i>
                            Update Trader
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
