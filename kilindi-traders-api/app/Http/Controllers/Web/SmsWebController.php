<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;
use App\Models\Trader;
use Illuminate\Http\Request;

class SmsWebController extends Controller
{
    public function index()
    {
        $smsLogs = SmsLog::with('trader')->latest()->paginate(10);
        
        $stats = [
            'total' => SmsLog::count(),
            'sent' => SmsLog::where('status', 'sent')->count(),
            'failed' => SmsLog::where('status', 'failed')->count(),
            'pending' => SmsLog::where('status', 'pending')->count(),
            'thisMonth' => SmsLog::whereMonth('created_at', now()->month)->count(),
            'successRate' => SmsLog::count() > 0 ? round((SmsLog::where('status', 'sent')->count() / SmsLog::count()) * 100, 1) : 0,
        ];
        
        return view('sms.index', compact('smsLogs', 'stats'));
    }

    public function create()
    {
        $traders = Trader::all();
        return view('sms.create', compact('traders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'trader_id' => 'nullable|exists:traders,id',
            'message' => 'required|string|max:160',
            'phone_number' => 'required|string|max:20',
            'sms_type' => 'required|string|in:reminder,notification,marketing,manual',
        ]);

        // Keep phone_number as is for database (table has phone_number column)
        // No field mapping needed

        $validated['status'] = 'pending';
        $validated['sent_at'] = now();

        SmsLog::create($validated);

        return redirect()->route('sms.index')
            ->with('success', 'SMS sent successfully!');
    }

    public function bulk()
    {
        $traders = Trader::all();
        return view('sms.bulk', compact('traders'));
    }

    public function sendBulk(Request $request)
    {
        $validated = $request->validate([
            'trader_ids' => 'required|array',
            'trader_ids.*' => 'exists:traders,id',
            'message' => 'required|string|max:160',
            'sms_type' => 'required|string|in:reminder,notification,marketing,manual',
        ]);

        $traders = Trader::whereIn('id', $validated['trader_ids'])->get();
        
        foreach ($traders as $trader) {
            SmsLog::create([
                'trader_id' => $trader->id,
                'phone_number' => $trader->phone_number,
                'message' => $validated['message'],
                'sms_type' => $validated['sms_type'],
                'status' => 'pending',
                'sent_at' => now(),
            ]);
        }

        return redirect()->route('sms.index')
            ->with('success', 'SMS za wingi zimetumwa kwa wachuuzi ' . count($traders) . ' kwa mafanikio!');
    }

    public function show(SmsLog $smsLog)
    {
        $smsLog->load('trader');
        return view('sms.show', compact('smsLog'));
    }
}
