@extends('layouts.app')

@section('title', 'Record Payment')

@section('content')
<header class="border-b border-border bg-card px-6 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-card-foreground">Record Payment</h1>
            <p class="text-muted-foreground">Record a new payment from a trader</p>
        </div>
        <a href="{{ route('payments.index') }}" class="text-muted-foreground hover:text-foreground flex items-center gap-2">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Back to Payments
        </a>
    </div>
</header>

<main class="flex-1 overflow-auto p-6">
    <div class="max-w-2xl mx-auto">
        <div class="bg-card border border-border rounded-lg shadow-sm">
            <div class="p-6">
                <form action="{{ route('payments.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label for="trader_id" class="text-sm font-medium text-foreground">Trader *</label>
                        <select id="trader_id" name="trader_id" required onchange="loadTraderData()"
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
                        <label for="amount" class="text-sm font-medium text-foreground">Amount (TSh) *</label>
                        <input type="number" id="amount" name="amount" required min="0" step="0.01"
                               value="{{ old('amount') }}"
                               placeholder="Enter payment amount"
                               class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('amount')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="payment_method" class="text-sm font-medium text-foreground">Payment Method *</label>
                        <select id="payment_method" name="payment_method" required onchange="toggleReferenceField()"
                                class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select payment method</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                        </select>
                        @error('payment_method')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2" id="reference-field" style="display: none;">
                        <label for="transaction_reference" class="text-sm font-medium text-foreground">Transaction Reference</label>
                        <input type="text" id="transaction_reference" name="transaction_reference"
                               value="{{ old('transaction_reference') }}"
                               placeholder="Enter transaction reference/ID"
                               class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('transaction_reference')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Type Selection -->
                    <div class="space-y-4">
                        <label class="text-sm font-medium text-foreground">Payment Type</label>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3">
                                <input type="radio" name="payment_type" value="general" checked onchange="togglePaymentType()"
                                       class="text-blue-500 focus:ring-blue-500">
                                <span class="text-sm">General Payment</span>
                            </label>
                            <label class="flex items-center gap-3">
                                <input type="radio" name="payment_type" value="debt" onchange="togglePaymentType()"
                                       class="text-blue-500 focus:ring-blue-500">
                                <span class="text-sm">Debt Payment</span>
                            </label>
                            <label class="flex items-center gap-3">
                                <input type="radio" name="payment_type" value="license" onchange="togglePaymentType()"
                                       class="text-blue-500 focus:ring-blue-500">
                                <span class="text-sm">License Fee Payment</span>
                            </label>
                        </div>
                    </div>

                    <!-- Debt Selection -->
                    <div class="space-y-2" id="debt-selection" style="display: none;">
                        <label for="debt_id" class="text-sm font-medium text-foreground">Outstanding Debt</label>
                        <select id="debt_id" name="debt_id"
                                class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select a debt to pay</option>
                            @foreach($debts as $debt)
                                <option value="{{ $debt->id }}" data-amount="{{ $debt->amount }}" data-trader="{{ $debt->trader_id }}">
                                    {{ $debt->trader->business_name }} - TSh {{ number_format($debt->amount) }}
                                    @if($debt->description) ({{ $debt->description }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- License Selection -->
                    <div class="space-y-2" id="license-selection" style="display: none;">
                        <label for="license_id" class="text-sm font-medium text-foreground">License</label>
                        <select id="license_id" name="license_id"
                                class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select a license</option>
                            @foreach($licenses as $license)
                                <option value="{{ $license->id }}" data-fee="{{ $license->fee }}" data-trader="{{ $license->trader_id }}">
                                    {{ $license->trader->business_name }} - {{ $license->license_type }} (TSh {{ number_format($license->fee) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="notes" class="text-sm font-medium text-foreground">Notes</label>
                        <textarea id="notes" name="notes" rows="3"
                                  placeholder="Additional notes about this payment"
                                  class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                        <a href="{{ route('payments.index') }}" 
                           class="px-4 py-2 text-sm font-medium text-muted-foreground hover:text-foreground border border-border rounded-lg hover:bg-muted">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-lg flex items-center gap-2">
                            <i data-lucide="credit-card" class="h-4 w-4"></i>
                            Record Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    function toggleReferenceField() {
        const method = document.getElementById('payment_method').value;
        const referenceField = document.getElementById('reference-field');
        
        if (method === 'bank_transfer' || method === 'mobile_money' || method === 'cheque') {
            referenceField.style.display = 'block';
            document.getElementById('transaction_reference').required = true;
        } else {
            referenceField.style.display = 'none';
            document.getElementById('transaction_reference').required = false;
        }
    }

    function togglePaymentType() {
        const selectedType = document.querySelector('input[name="payment_type"]:checked').value;
        const debtSelection = document.getElementById('debt-selection');
        const licenseSelection = document.getElementById('license-selection');
        
        // Reset selections
        document.getElementById('debt_id').value = '';
        document.getElementById('license_id').value = '';
        
        if (selectedType === 'debt') {
            debtSelection.style.display = 'block';
            licenseSelection.style.display = 'none';
        } else if (selectedType === 'license') {
            debtSelection.style.display = 'none';
            licenseSelection.style.display = 'block';
        } else {
            debtSelection.style.display = 'none';
            licenseSelection.style.display = 'none';
        }
    }

    function loadTraderData() {
        const traderId = document.getElementById('trader_id').value;
        
        if (traderId) {
            // Filter debts and licenses for selected trader
            const debtSelect = document.getElementById('debt_id');
            const licenseSelect = document.getElementById('license_id');
            
            Array.from(debtSelect.options).forEach(option => {
                if (option.value && option.dataset.trader !== traderId) {
                    option.style.display = 'none';
                } else {
                    option.style.display = 'block';
                }
            });
            
            Array.from(licenseSelect.options).forEach(option => {
                if (option.value && option.dataset.trader !== traderId) {
                    option.style.display = 'none';
                } else {
                    option.style.display = 'block';
                }
            });
        }
    }

    // Auto-fill amount when debt or license is selected
    document.getElementById('debt_id').addEventListener('change', function() {
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('amount').value = selectedOption.dataset.amount;
        }
    });

    document.getElementById('license_id').addEventListener('change', function() {
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('amount').value = selectedOption.dataset.fee;
        }
    });

    // Initialize form state
    document.addEventListener('DOMContentLoaded', function() {
        toggleReferenceField();
        togglePaymentType();
    });
</script>
@endsection
