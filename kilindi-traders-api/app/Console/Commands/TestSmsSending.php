<?php

namespace App\Console\Commands;

use App\Models\Trader;
use App\Services\SmsService;
use Illuminate\Console\Command;

class TestSmsSending extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:test {phone} {message?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test SMS sending functionality with Africa\'s Talking';

    /**
     * Execute the console command.
     */
    public function handle(SmsService $smsService)
    {
        $phone = $this->argument('phone');
        $message = $this->argument('message') ?? 'Hii ni jaribio la ujumbe kutoka kwa Halmashauri ya Wilaya ya Kilindi. Asante!';

        $this->info("Attempting to send SMS to: " . $phone);
        $this->info("Message: " . $message);

        try {
            // Create a dummy trader for testing
            $trader = new Trader([
                'owner_name' => 'Jaribio',
                'business_name' => 'Biashara ya Jaribio',
                'phone_number' => $phone,
                'email' => 'test@example.com',
                'location' => 'Kilindi',
                'license_number' => 'TEST123',
                'license_expiry_date' => now()->addYear(),
            ]);

            // Send the test message
            $result = $smsService->sendSms($phone, $message, 'test', null);

            if ($result) {
                $this->info('✅ SMS sent successfully!');
                $this->info('Check your phone for the test message.');
            } else {
                $this->error('❌ Failed to send SMS. Check the logs for more details.');
            }
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}
