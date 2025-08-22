@extends('layouts.app')

@section('title', 'Angalia SMS')

@section('content')
<header class="border-b border-border bg-card px-6 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-6">SMS Details</h1>
            <p class="text-muted-foreground">Angalia maelezo kamili ya ujumbe wa SMS</p>
            <a href="{{ route('sms.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                <i data-lucide="arrow-left" class="w-4 h-4 inline mr-2"></i>
                Back
            </a>
        </div>
    </div>
</header>

<main class="flex-1 overflow-auto p-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-card border border-border rounded-lg shadow-sm">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- SMS Details -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">SMS Information</h2>
                        
                        <div class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">SMS ID:</dt>
                                <p class="text-sm text-gray-800">#{{ $smsLog->id }}</p>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Phone Number:</dt>
                                <p class="text-sm text-gray-800">{{ $smsLog->phone_number }}</p>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Message Type:</dt>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($smsLog->sms_type === 'payment_reminder') bg-blue-100 text-blue-800
                                    @elseif($smsLog->sms_type === 'license_expiry') bg-yellow-100 text-yellow-800
                                    @elseif($smsLog->sms_type === 'welcome') bg-green-100 text-green-800
                                    @elseif($smsLog->sms_type === 'notification') bg-purple-100 text-purple-800
                                    @elseif($smsLog->sms_type === 'marketing') bg-orange-100 text-orange-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @if($smsLog->sms_type === 'payment_reminder')
                                        Payment Reminder
                                    @elseif($smsLog->sms_type === 'license_expiry')
                                        License Expiry Reminder
                                    @elseif($smsLog->sms_type === 'welcome')
                                        Welcome Message
                                    @elseif($smsLog->sms_type === 'notification')
                                        Notification
                                    @elseif($smsLog->sms_type === 'marketing')
                                        Marketing
                                    @else
                                        Custom Message
                                    @endif
                                </span>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status:</dt>
                                @if($smsLog->status === 'sent')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Sent
                                    </span>
                                @elseif($smsLog->status === 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Failed
                                    </span>
                                @endif
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Sent Date:</dt>
                                <p class="text-sm text-gray-800">{{ $smsLog->sent_at ? $smsLog->sent_at->format('d/m/Y H:i') : 'Not sent yet' }}</p>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created:</dt>
                                <p class="text-sm text-gray-800">{{ $smsLog->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Trader Details -->
                    @if($smsLog->trader)
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Trader Information</h2>
                        
                        <div class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Business Name:</dt>
                                <p class="text-sm text-gray-800">{{ $smsLog->trader->business_name }}</p>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Owner Name:</dt>
                                <p class="text-sm text-gray-800">{{ $smsLog->trader->owner_name }}</p>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Business Type:</dt>
                                <p class="text-sm text-gray-800">{{ $smsLog->trader->business_type }}</p>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Business Location:</dt>
                                <p class="text-sm text-gray-800">{{ $smsLog->trader->business_location }}</p>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Recipient Information</h2>
                        <p class="text-sm text-gray-600">This SMS was not associated with any trader.</p>
                    </div>
                    @endif
                </div>
                
                <!-- Message Content -->
                <div class="mt-6 pt-6 border-t border-border">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Message Content</h3>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ $smsLog->message }}</p>
                    </div>
                    <div class="mt-2 flex justify-between text-xs text-muted-foreground">
                        <span>{{ strlen($smsLog->message) }} characters</span>
                        <span>{{ ceil(strlen($smsLog->message) / 160) }} SMS</span>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="mt-6 pt-6 border-t border-border flex items-center justify-between">
                    <a href="{{ route('sms.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                        <i data-lucide="arrow-left" class="h-4 w-4"></i>
                        Back to List
                    </a>
                    
                    @if($smsLog->trader)
                    <a href="{{ route('traders.show', $smsLog->trader) }}" 
                       class="px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700 border border-blue-200 rounded-lg hover:bg-blue-50 flex items-center gap-2">
                        <i data-lucide="user" class="h-4 w-4"></i>
                        View Trader
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
