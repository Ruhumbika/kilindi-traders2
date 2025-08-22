@extends('layouts.app')

@section('title', 'Edit Debt')

@section('content')
<header class="border-b border-border bg-card px-6 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-card-foreground">Edit Debt</h1>
            <p class="text-muted-foreground">Update debt information</p>
        </div>
        <a href="{{ route('debts.index') }}" class="text-muted-foreground hover:text-foreground flex items-center gap-2">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Back to Debts
        </a>
    </div>
</header>

<main class="flex-1 overflow-auto p-6">
    <div class="max-w-2xl mx-auto">
        <div class="bg-card border border-border rounded-lg shadow-sm">
            <div class="p-6">
                <form action="{{ route('debts.update', $debt) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-2">
                        <label for="trader_id" class="text-sm font-medium text-foreground">Trader *</label>
                        <select id="trader_id" name="trader_id" required 
                                class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select a trader</option>
                            @foreach($traders as $trader)
                                <option value="{{ $trader->id }}" {{ old('trader_id', $debt->trader_id) == $trader->id ? 'selected' : '' }}>
                                    {{ $trader->business_name }} - {{ $trader->owner_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('trader_id')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="amount" class="text-sm font-medium text-foreground">Amount (TSh) *</label>
                        <input type="number" id="amount" name="amount" required min="0" step="0.01"
                               value="{{ old('amount', $debt->amount) }}"
                               placeholder="Enter debt amount"
                               class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('amount')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="description" class="text-sm font-medium text-foreground">Description</label>
                        <textarea id="description" name="description" rows="3"
                                  placeholder="Enter debt description or reason"
                                  class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $debt->description) }}</textarea>
                        @error('description')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="due_date" class="text-sm font-medium text-foreground">Due Date</label>
                        <input type="date" id="due_date" name="due_date"
                               value="{{ old('due_date', $debt->due_date ? $debt->due_date->format('Y-m-d') : '') }}"
                               class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('due_date')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="status" class="text-sm font-medium text-foreground">Status</label>
                        <select id="status" name="status" 
                                class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="pending" {{ old('status', $debt->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ old('status', $debt->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="overdue" {{ old('status', $debt->status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        </select>
                        @error('status')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                        <a href="{{ route('debts.index') }}" 
                           class="px-4 py-2 text-sm font-medium text-muted-foreground hover:text-foreground border border-border rounded-lg hover:bg-muted">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-lg flex items-center gap-2">
                            <i data-lucide="save" class="h-4 w-4"></i>
                            Update Debt
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
