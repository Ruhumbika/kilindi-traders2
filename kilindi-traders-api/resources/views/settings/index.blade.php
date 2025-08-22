@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<header class="border-b border-border bg-card px-6 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-card-foreground">System Settings</h1>
            <p class="text-muted-foreground">Configure application preferences and behavior</p>
        </div>
    </div>
</header>

<main class="flex-1 overflow-auto p-6">
    <div class="max-w-4xl mx-auto space-y-6">
        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- General Settings -->
            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="p-6 border-b border-border">
                    <h3 class="font-semibold">General Settings</h3>
                    <p class="text-sm text-muted-foreground">Basic application configuration</p>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="app_name" class="text-sm font-medium text-foreground">Application Name</label>
                            <input type="text" id="app_name" name="app_name" 
                                   value="{{ old('app_name', $settings['app_name']) }}"
                                   class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div class="space-y-2">
                            <label for="timezone" class="text-sm font-medium text-foreground">Timezone</label>
                            <select id="timezone" name="timezone"
                                    class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="UTC" {{ $settings['timezone'] == 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="Africa/Dar_es_Salaam" {{ $settings['timezone'] == 'Africa/Dar_es_Salaam' ? 'selected' : '' }}>East Africa Time</option>
                                <option value="Africa/Nairobi" {{ $settings['timezone'] == 'Africa/Nairobi' ? 'selected' : '' }}>East Africa Time (Nairobi)</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="currency" class="text-sm font-medium text-foreground">Currency Symbol</label>
                        <input type="text" id="currency" name="currency" 
                               value="{{ old('currency', $settings['currency']) }}"
                               class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="p-6 border-b border-border">
                    <h3 class="font-semibold">Notification Settings</h3>
                    <p class="text-sm text-muted-foreground">Configure alerts and reminders</p>
                </div>
                <div class="p-6 space-y-6">
                    <div class="space-y-4">
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="sms_enabled" value="1" 
                                   {{ $settings['sms_enabled'] ? 'checked' : '' }}
                                   class="text-blue-500 focus:ring-blue-500">
                            <div>
                                <span class="text-sm font-medium">Enable SMS Notifications</span>
                                <p class="text-xs text-muted-foreground">Allow sending SMS messages to traders</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="email_notifications" value="1" 
                                   {{ $settings['email_notifications'] ? 'checked' : '' }}
                                   class="text-blue-500 focus:ring-blue-500">
                            <div>
                                <span class="text-sm font-medium">Email Notifications</span>
                                <p class="text-xs text-muted-foreground">Send email alerts for important events</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="auto_debt_reminders" value="1" 
                                   {{ $settings['auto_debt_reminders'] ? 'checked' : '' }}
                                   class="text-blue-500 focus:ring-blue-500">
                            <div>
                                <span class="text-sm font-medium">Automatic Debt Reminders</span>
                                <p class="text-xs text-muted-foreground">Send automatic SMS reminders for overdue debts</p>
                            </div>
                        </label>
                    </div>

                    <div class="space-y-2">
                        <label for="license_expiry_alerts" class="text-sm font-medium text-foreground">License Expiry Alert (Days Before)</label>
                        <input type="number" id="license_expiry_alerts" name="license_expiry_alerts" min="1" max="90"
                               value="{{ old('license_expiry_alerts', $settings['license_expiry_alerts']) }}"
                               class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-xs text-muted-foreground">Send alerts this many days before license expiry</p>
                    </div>
                </div>
            </div>

            <!-- System Settings -->
            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="p-6 border-b border-border">
                    <h3 class="font-semibold">System Settings</h3>
                    <p class="text-sm text-muted-foreground">System maintenance and backup configuration</p>
                </div>
                <div class="p-6 space-y-6">
                    <div class="space-y-2">
                        <label for="backup_frequency" class="text-sm font-medium text-foreground">Backup Frequency</label>
                        <select id="backup_frequency" name="backup_frequency"
                                class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="daily" {{ $settings['backup_frequency'] == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ $settings['backup_frequency'] == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ $settings['backup_frequency'] == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        </select>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <i data-lucide="shield-check" class="h-5 w-5 text-yellow-500 mt-0.5"></i>
                            <div class="text-sm text-yellow-800">
                                <p class="font-medium mb-1">System Information</p>
                                <ul class="space-y-1 text-yellow-700">
                                    <li>• Laravel Version: {{ app()->version() }}</li>
                                    <li>• PHP Version: {{ phpversion() }}</li>
                                    <li>• Database: PostgreSQL</li>
                                    <li>• Last Backup: Not configured</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SMS Configuration -->
            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="p-6 border-b border-border">
                    <h3 class="font-semibold">SMS Configuration</h3>
                    <p class="text-sm text-muted-foreground">SMS service provider settings</p>
                </div>
                <div class="p-6 space-y-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <i data-lucide="info" class="h-5 w-5 text-blue-500 mt-0.5"></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">SMS Service Status</p>
                                <ul class="space-y-1 text-blue-700">
                                    <li>• Provider: Mock SMS Service (Development)</li>
                                    <li>• Status: {{ $settings['sms_enabled'] ? 'Enabled' : 'Disabled' }}</li>
                                    <li>• Rate: TSh 50 per SMS</li>
                                    <li>• Balance: TSh 10,000 (Mock)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <button type="button" onclick="resetToDefaults()" 
                        class="px-4 py-2 text-sm font-medium text-muted-foreground hover:text-foreground border border-border rounded-lg hover:bg-muted">
                    Reset to Defaults
                </button>
                <button type="submit" 
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-lg flex items-center gap-2">
                    <i data-lucide="save" class="h-4 w-4"></i>
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</main>

<script>
    function resetToDefaults() {
        if (confirm('Are you sure you want to reset all settings to default values?')) {
            document.getElementById('app_name').value = 'Kilindi Traders';
            document.getElementById('timezone').value = 'UTC';
            document.getElementById('currency').value = 'TSh';
            document.getElementById('license_expiry_alerts').value = '30';
            document.getElementById('backup_frequency').value = 'daily';
            
            // Reset checkboxes
            document.querySelector('input[name="sms_enabled"]').checked = true;
            document.querySelector('input[name="email_notifications"]').checked = true;
            document.querySelector('input[name="auto_debt_reminders"]').checked = true;
        }
    }
</script>
@endsection
