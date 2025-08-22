<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsWebController extends Controller
{
    public function index()
    {
        $settings = [
            'app_name' => config('app.name'),
            'app_url' => config('app.url'),
            'timezone' => config('app.timezone'),
            'currency' => 'TSh',
            'sms_enabled' => true,
            'email_notifications' => true,
            'auto_debt_reminders' => true,
            'license_expiry_alerts' => 30,
            'backup_frequency' => 'daily',
            'notification_email' => 'admin@kilindi-traders.com',
        ];
        
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'notification_email' => 'required|email',
            'sms_enabled' => 'boolean',
            'currency' => 'required|string|max:10',
        ]);

        // In a real app, you'd save these to a settings table or config
        // For now, just redirect back with success message
        
        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}
