@extends('layouts.app')

@section('title', 'Send SMS')

@section('content')
<header class="border-b border-border bg-card px-6 py-4">
    <div class="flex items-center justify-between">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Send SMS</h1>
            <p class="text-muted-foreground">Send SMS message to a trader</p>
            <a href="{{ route('sms.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-2">
                <i data-lucide="arrow-left" class="w-4 h-4 inline mr-2"></i>
                Back
            </a>
        </div>
    </div>
</header>

<main class="flex-1 overflow-auto p-6">
    <div class="max-w-2xl mx-auto">
        <div class="bg-card border border-border rounded-lg shadow-sm">
            <div class="p-6">
                <form action="{{ route('sms.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-4">
                        <label class="text-sm font-medium text-foreground">Recipient Type</label>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3">
                                <input type="radio" name="recipient_type" value="trader" checked onchange="toggleRecipientType()"
                                       class="text-blue-500 focus:ring-blue-500">
                                <span class="text-sm">Select Trader</span>
                            </label>
                            <label class="flex items-center gap-3">
                                <input type="radio" name="recipient_type" value="manual" onchange="toggleRecipientType()"
                                       class="text-blue-500 focus:ring-blue-500">
                                <span class="text-sm">Manual Phone Number</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-2" id="trader-selection">
                        <label for="trader_id" class="block text-sm font-medium text-gray-700 mb-2">Trader (Optional)</label>
                        <select id="trader_id" name="trader_id" onchange="updatePhoneNumber()"
                                class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select trader...</option>
                            @foreach($traders as $trader)
                                <option value="{{ $trader->id }}" data-phone="{{ $trader->phone_number }}" {{ old('trader_id') == $trader->id ? 'selected' : '' }}>
                                    {{ $trader->business_name }} - {{ $trader->owner_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('trader_id')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" id="phone_number" name="phone_number" required
                               value="{{ old('phone_number') }}"
                               placeholder="+255 XXX XXX XXX"
                               class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('phone_number')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="sms_type" class="text-sm font-medium text-foreground">SMS Type *</label>
                        <select id="sms_type" name="sms_type" required onchange="updateMessageTemplate()"
                                class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="custom">Custom Message</option>
                            <option value="payment_reminder">Payment Reminder</option>
                            <option value="license_expiry">License Expiry Reminder</option>
                            <option value="welcome">Welcome Message</option>
                            <option value="notification" {{ old('sms_type') == 'notification' ? 'selected' : '' }}>Notification</option>
                            <option value="marketing" {{ old('sms_type') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                            <option value="manual" {{ old('sms_type') == 'manual' ? 'selected' : '' }}>Manual</option>
                        </select>
                        @error('sms_type')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message Type</label>
                        <textarea id="message" name="message" rows="4" required maxlength="160"
                                  placeholder="Enter your SMS message (max 160 characters)"
                                  class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('message') }}</textarea>
                        <div class="flex justify-between text-xs text-muted-foreground">
                            <span id="char-count">0/160 characters</span>
                            <span id="sms-count">1 SMS</span>
                        </div>
                        @error('message')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <i data-lucide="info" class="h-5 w-5 text-blue-500 mt-0.5"></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">SMS Templates</p>
                                <div class="space-y-1 text-blue-700">
                                    <button type="button" onclick="useTemplate('reminder')" class="block hover:underline">• Ujumbe wa ukumbusho wa malipo</button>
                                    <button type="button" onclick="useTemplate('license')" class="block hover:underline">• Ukumbusho wa kuisha kwa leseni</button>
                                    <button type="button" onclick="useTemplate('welcome')" class="block hover:underline">• Ujumbe wa kukaribisha</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                        <a href="{{ route('sms.index') }}" 
                           class="px-4 py-2 text-sm font-medium text-muted-foreground hover:text-foreground border border-border rounded-lg hover:bg-muted">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            <i data-lucide="send" class="w-4 h-4 inline mr-2"></i>
                            Send SMS
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    const templates = {
        reminder: "Mpendwa mfanyabiashara, una deni la TSh {amount} kwa Halmashauri ya Wilaya ya Kilindi. Tafadhali lipa kabla ya tarehe {date}. Asante.",
        license: "Mpendwa mfanyabiashara, leseni yako ya biashara itaisha tarehe {date}. Tafadhali ifanye upya ili kuepuka faini.",
        welcome: "Karibu Halmashauri ya Wilaya ya Kilindi! Biashara yako {business_name} imesajiliwa kwa mafanikio. Tunatarajia kufanya kazi nawe."
    };

    function toggleRecipientType() {
        const recipientType = document.querySelector('input[name="recipient_type"]:checked').value;
        const traderSelection = document.getElementById('trader-selection');
        const traderSelect = document.getElementById('trader_id');
        const phoneInput = document.getElementById('phone_number');
        
        if (recipientType === 'trader') {
            traderSelection.style.display = 'block';
            traderSelect.required = true;
            phoneInput.readOnly = true;
        } else {
            traderSelection.style.display = 'none';
            traderSelect.required = false;
            traderSelect.value = '';
            phoneInput.readOnly = false;
            phoneInput.value = '';
        }
    }

    function updatePhoneNumber() {
        const traderSelect = document.getElementById('trader_id');
        const phoneInput = document.getElementById('phone_number');
        const selectedOption = traderSelect.options[traderSelect.selectedIndex];
        
        if (selectedOption.dataset.phone) {
            phoneInput.value = selectedOption.dataset.phone;
        }
    }

    function updateMessageTemplate() {
        const smsType = document.getElementById('sms_type').value;
        const messageTextarea = document.getElementById('message');
        
        if (smsType && templates[smsType] && !messageTextarea.value) {
            messageTextarea.value = templates[smsType];
            updateCharCount();
        }
    }

    function useTemplate(templateType) {
        const messageTextarea = document.getElementById('message');
        messageTextarea.value = templates[templateType];
        updateCharCount();
    }

    function updateCharCount() {
        const message = document.getElementById('message').value;
        const charCount = message.length;
        const smsCount = Math.ceil(charCount / 160);
        
        document.getElementById('char-count').textContent = `${charCount}/160 characters`;
        document.getElementById('sms-count').textContent = `${smsCount} SMS`;
        
        if (charCount > 160) {
            document.getElementById('char-count').classList.add('text-red-600');
        } else {
            document.getElementById('char-count').classList.remove('text-red-600');
        }
    }

    document.getElementById('message').addEventListener('input', updateCharCount);
    
    document.addEventListener('DOMContentLoaded', function() {
        toggleRecipientType();
        updateCharCount();
    });
</script>
@endsection
