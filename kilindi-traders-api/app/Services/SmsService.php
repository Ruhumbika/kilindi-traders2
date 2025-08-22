<?php

namespace App\Services;

use App\Models\SmsLog;
use App\Models\Trader;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use AfricasTalking\SDK\AfricasTalking;

// ========================================
// SMS SERVICE CONFIGURATION
// ========================================
// To use real SMS APIs, add these to your .env file:
//
// For Twilio:
// TWILIO_SID=your_twilio_sid
// TWILIO_TOKEN=your_twilio_token
// TWILIO_FROM=+1234567890
//
// For Africa's Talking:
// AFRICASTALKING_USERNAME=your_username
// AFRICASTALKING_API_KEY=your_api_key
// AFRICASTALKING_SHORTCODE=your_shortcode
//
// For Generic SMS API:
// SMS_ENDPOINT=https://api.sms-provider.com/send
// SMS_API_KEY=your_api_key
// SMS_SENDER_ID=KILINDI
// ========================================

class SmsService
{
    // ========================================
    // SMS PROVIDER SETUP INSTRUCTIONS
    // ========================================
    // 1. Choose your SMS provider (Twilio, Africa's Talking, etc.)
    // 2. Install the provider's PHP SDK via Composer
    // 3. Add credentials to config/services.php
    // 4. Uncomment the appropriate integration code in sendSms() method
    // 5. Remove the mock/development code
    // ========================================
    public function sendWelcomeMessage(Trader $trader)
    {
        $message = "Karibu Halmashauri ya Wilaya ya Kilindi! Biashara yako '{$trader->business_name}' imesajiliwa kwa mafanikio. Tunatarajia kufanya kazi nawe.";
        
        return $this->sendSms($trader->phone_number, $message, 'notification', $trader->id);
    }

    public function sendDebtReminder(Trader $trader, $debtAmount, $controlNumber = null)
    {
        $message = "Mpendwa {$trader->owner_name}, una deni la TSh " . number_format($debtAmount) . " kwa Halmashauri ya Wilaya ya Kilindi.";
        
        if ($controlNumber) {
            $message .= " Lipa kwa kutumia namba ya udhibiti: {$controlNumber}. Rejea: {$controlNumber}";
        }
        
        $message .= " Wasiliana nasi kwa msaada wa malipo.";
        
        return $this->sendSms($trader->phone_number, $message, 'reminder', $trader->id);
    }

    public function sendPaymentConfirmation(Trader $trader, $paymentAmount)
    {
        $message = "Malipo yamethibitishwa! Asante {$trader->owner_name} kwa malipo yako ya TSh " . number_format($paymentAmount) . ". Akaunti yako imesasishwa.";
        
        return $this->sendSms($trader->phone_number, $message, 'notification', $trader->id);
    }

    public function sendLicenseExpiry(Trader $trader, $expiryDate, $controlNumber = null)
    {
        $message = "Mpendwa {$trader->owner_name}, leseni yako ya biashara itaisha tarehe " . date('d/m/Y', strtotime($expiryDate)) . ". Tafadhali ifanye upya kwa Halmashauri ya Wilaya ya Kilindi.";
        
        if ($controlNumber) {
            $message .= " Lipa ada ya kufanya upya kwa kutumia namba ya udhibiti: {$controlNumber}. Rejea: {$controlNumber}";
        }
        
        return $this->sendSms($trader->phone_number, $message, 'reminder', $trader->id);
    }

    public function sendOverdueNotice(Trader $trader, $overdueAmount, $dueDate, $controlNumber = null)
    {
        $message = "MUHIMU: Mpendwa {$trader->owner_name}, deni lako la TSh " . number_format($overdueAmount) . " kwa Halmashauri ya Wilaya ya Kilindi lilikuwa na muda wa kulipwa tarehe " . date('d/m/Y', strtotime($dueDate)) . ".";
        
        if ($controlNumber) {
            $message .= " Lipa haraka kwa kutumia namba ya udhibiti: {$controlNumber}. Rejea: {$controlNumber}";
        }
        
        $message .= " Wasiliana nasi kutatua jambo hili.";
        
        return $this->sendSms($trader->phone_number, $message, 'reminder', $trader->id);
    }

    public function sendSms($phoneNumber, $message, $type, $traderId = null)
    {
        // Format phone number for Africa's Talking (add + if not present)
        $formattedNumber = $phoneNumber;
        if (strpos($phoneNumber, '+') !== 0) {
            // Add +255 for Tanzania numbers if no country code is present
            if (strpos($phoneNumber, '0') === 0) {
                $formattedNumber = '+255' . substr($phoneNumber, 1);
            } else {
                $formattedNumber = '+' . ltrim($phoneNumber, '+');
            }
        }

        // Log the SMS - make trader_id nullable to handle cases where trader might not be available
        $smsLog = SmsLog::create([
            'trader_id' => $traderId, // This can be null for system-wide messages
            'phone_number' => $formattedNumber,
            'message' => $message,
            'sms_type' => $type,
            'status' => 'pending',
            'sent_at' => null, // Will be set when actually sent
        ]);

        // ========================================
        // AFRICA'S TALKING SMS INTEGRATION
        // ========================================
        try {
            // Initialize Africa's Talking API credentials
            $username = config('services.africastalking.username');
            $apiKey = config('services.africastalking.api_key');
            $shortcode = config('services.africastalking.shortcode');
            
            // Create a new instance of the Africa's Talking SDK
            $AT = new AfricasTalking($username, $apiKey);
            
            // Get the SMS service
            $sms = $AT->sms();
            
            // Send the SMS
            $result = $sms->send([
                'to' => $formattedNumber,
                'message' => $message,
                'from' => $shortcode
            ]);
            
            Log::info('Africa\'s Talking SDK Response', [
                'phone' => $formattedNumber,
                'response' => $result,
            ]);
            
            // Update SMS log with success
            $smsLog->update([
                'status' => 'sent',
                'sent_at' => now(),
                'provider_response' => json_encode($result),
                'provider_status' => 200
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            // Log the error
            Log::error('Failed to send SMS via Africa\'s Talking SDK', [
                'error' => $e->getMessage(),
                'phone_number' => $formattedNumber,
                'message' => $message
            ]);
            
            // Update SMS log with failure
            $smsLog->update([
                'status' => 'failed',
                'provider_response' => $e->getMessage()
            ]);
            
            return false;
        }
            
            // OPTION 3: For Generic HTTP API (like Nexmo, MessageBird, etc.)
            /*
            $response = Http::post(config('services.sms.endpoint'), [
                'api_key' => config('services.sms.api_key'),
                'to' => $phoneNumber,
                'message' => $message,
                'from' => config('services.sms.sender_id')
            ]);
            
            if ($response->successful()) {
                $smsLog->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                    'provider_response' => $response->body()
                ]);
            } else {
                throw new \Exception('SMS API request failed: ' . $response->body());
            }
            */
            
        // End of try block - this should not be reached in production
        return false;
    }
}
