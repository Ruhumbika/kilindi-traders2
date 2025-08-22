@extends('layouts.app')

@section('title', 'Bulk SMS')

@section('content')
<header class="border-b border-border bg-card px-6 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-card-foreground">Bulk SMS</h1>
            <p class="text-muted-foreground">Send SMS to multiple traders at once</p>
        </div>
        <a href="{{ route('sms.index') }}" class="text-muted-foreground hover:text-foreground flex items-center gap-2">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Back to SMS
        </a>
    </div>
</header>

<main class="flex-1 overflow-auto p-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-card border border-border rounded-lg shadow-sm">
            <div class="p-6">
                <form action="{{ route('sms.send-bulk') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-foreground">Select Recipients *</label>
                        <div class="border border-border rounded-lg p-4 max-h-64 overflow-y-auto">
                            <div class="flex items-center gap-2 mb-3">
                                <input type="checkbox" id="select-all" onchange="toggleSelectAll()" class="text-blue-500 focus:ring-blue-500">
                                <label for="select-all" class="text-sm font-medium">Select All Traders</label>
                            </div>
                            <div class="space-y-2">
                                @foreach($traders as $trader)
                                    <label class="flex items-center gap-3 p-2 hover:bg-muted rounded">
                                        <input type="checkbox" name="trader_ids[]" value="{{ $trader->id }}" 
                                               class="trader-checkbox text-blue-500 focus:ring-blue-500">
                                        <div class="flex-1">
                                            <p class="font-medium">{{ $trader->business_name }}</p>
                                            <p class="text-sm text-muted-foreground">{{ $trader->owner_name }} • {{ $trader->phone }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <p class="text-sm text-muted-foreground">Selected: <span id="selected-count">0</span> traders</p>
                        @error('trader_ids')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="sms_type" class="text-sm font-medium text-foreground">SMS Type *</label>
                        <select id="sms_type" name="sms_type" required onchange="updateBulkTemplate()"
                                class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select SMS type</option>
                            <option value="reminder" {{ old('sms_type') == 'reminder' ? 'selected' : '' }}>Payment Reminder</option>
                            <option value="notification" {{ old('sms_type') == 'notification' ? 'selected' : '' }}>Notification</option>
                            <option value="marketing" {{ old('sms_type') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                            <option value="manual" {{ old('sms_type') == 'manual' ? 'selected' : '' }}>Manual</option>
                        </select>
                        @error('sms_type')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="message" class="text-sm font-medium text-foreground">Message *</label>
                        <textarea id="message" name="message" rows="4" required maxlength="160"
                                  placeholder="Enter your bulk SMS message (max 160 characters)"
                                  class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('message') }}</textarea>
                        <div class="flex justify-between text-xs text-muted-foreground">
                            <span id="char-count">0/160 characters</span>
                            <span>Cost estimate: TSh <span id="cost-estimate">0</span></span>
                        </div>
                        @error('message')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <i data-lucide="alert-triangle" class="h-5 w-5 text-amber-500 mt-0.5"></i>
                            <div class="text-sm text-amber-800">
                                <p class="font-medium mb-1">Bulk SMS Warning</p>
                                <ul class="space-y-1 text-amber-700">
                                    <li>• This will send SMS to all selected traders</li>
                                    <li>• SMS charges apply per message sent</li>
                                    <li>• Messages cannot be recalled once sent</li>
                                    <li>• Ensure message content is appropriate</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                        <a href="{{ route('sms.index') }}" 
                           class="px-4 py-2 text-sm font-medium text-muted-foreground hover:text-foreground border border-border rounded-lg hover:bg-muted">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-lg flex items-center gap-2">
                            <i data-lucide="send" class="h-4 w-4"></i>
                            Send Bulk SMS
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    const bulkTemplates = {
        reminder: "Dear valued trader, please settle your outstanding payments to continue enjoying our services. Thank you.",
        notification: "Important update from Kilindi District Trading Office. Please visit our office for more information.",
        marketing: "Special offer for registered traders! Contact us to learn about new opportunities and benefits.",
        manual: ""
    };

    function toggleSelectAll() {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.trader-checkbox');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
        
        updateSelectedCount();
    }

    function updateSelectedCount() {
        const checkboxes = document.querySelectorAll('.trader-checkbox:checked');
        const count = checkboxes.length;
        document.getElementById('selected-count').textContent = count;
        
        // Update cost estimate
        const message = document.getElementById('message').value;
        const smsCount = Math.ceil(message.length / 160) || 1;
        const totalCost = count * smsCount * 50; // 50 TSh per SMS
        document.getElementById('cost-estimate').textContent = totalCost.toLocaleString();
    }

    function updateBulkTemplate() {
        const smsType = document.getElementById('sms_type').value;
        const messageTextarea = document.getElementById('message');
        
        if (smsType && bulkTemplates[smsType] && !messageTextarea.value) {
            messageTextarea.value = bulkTemplates[smsType];
            updateCharCount();
        }
    }

    function updateCharCount() {
        const message = document.getElementById('message').value;
        const charCount = message.length;
        
        document.getElementById('char-count').textContent = `${charCount}/160 characters`;
        
        if (charCount > 160) {
            document.getElementById('char-count').classList.add('text-red-600');
        } else {
            document.getElementById('char-count').classList.remove('text-red-600');
        }
        
        updateSelectedCount();
    }

    // Event listeners
    document.getElementById('message').addEventListener('input', updateCharCount);
    
    document.querySelectorAll('.trader-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        updateCharCount();
        updateSelectedCount();
    });
</script>
@endsection
