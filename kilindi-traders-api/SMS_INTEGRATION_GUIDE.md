# SMS API Integration Guide for Kilindi Traders

## Overview
This guide shows you exactly where and how to integrate real SMS APIs into the Kilindi Traders system. Currently, the system uses mock SMS sending for development.

## Integration Points

### 1. Main SMS Service File
**Location**: `app/Services/SmsService.php`
**Line**: 93-201 (in the `sendSms()` method)

### 2. Configuration File
**Location**: `config/services.php`
**Lines**: 38-63 (SMS service configurations)

## Step-by-Step Integration

### Option 1: Twilio SMS (Recommended for Global Use)

#### Step 1: Install Twilio SDK
```bash
composer require twilio/sdk
```

#### Step 2: Add to .env file
```env
TWILIO_SID=your_account_sid_here
TWILIO_TOKEN=your_auth_token_here
TWILIO_FROM=+1234567890
```

#### Step 3: Uncomment Twilio code in SmsService.php
Find lines 111-132 and uncomment the Twilio integration code:
```php
$twilio = new \Twilio\Rest\Client(
    config('services.twilio.sid'),
    config('services.twilio.token')
);

$twilioMessage = $twilio->messages->create(
    $phoneNumber, // To number
    [
        'from' => config('services.twilio.from'),
        'body' => $message
    ]
);

// Update SMS log with success
$smsLog->update([
    'status' => 'sent',
    'sent_at' => now(),
    'provider_id' => $twilioMessage->sid
]);
```

#### Step 4: Remove mock code
Comment out or remove lines 176-181 (the mock/development code)

---

### Option 2: Africa's Talking (Best for Tanzania/Kenya)

#### Step 1: Install Africa's Talking SDK
```bash
composer require africastalking/africastalking
```

#### Step 2: Add to .env file
```env
AFRICASTALKING_USERNAME=your_username
AFRICASTALKING_API_KEY=your_api_key
AFRICASTALKING_SHORTCODE=your_shortcode
```

#### Step 3: Uncomment Africa's Talking code in SmsService.php
Find lines 134-154 and uncomment the Africa's Talking integration code:
```php
$gateway = new \AfricasTalking\SDK\AfricasTalking(
    config('services.africastalking.username'),
    config('services.africastalking.api_key')
);

$sms = $gateway->sms();
$result = $sms->send([
    'to' => $phoneNumber,
    'message' => $message,
    'from' => config('services.africastalking.shortcode')
]);

// Update SMS log with success
$smsLog->update([
    'status' => 'sent',
    'sent_at' => now(),
    'provider_response' => json_encode($result)
]);
```

#### Step 4: Remove mock code
Comment out or remove lines 176-181 (the mock/development code)

---

### Option 3: Generic HTTP API (Nexmo, MessageBird, etc.)

#### Step 1: Add to .env file
```env
SMS_ENDPOINT=https://api.your-sms-provider.com/send
SMS_API_KEY=your_api_key
SMS_SENDER_ID=KILINDI
```

#### Step 2: Uncomment Generic HTTP code in SmsService.php
Find lines 156-174 and uncomment the HTTP API integration code:
```php
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
```

#### Step 3: Remove mock code
Comment out or remove lines 176-181 (the mock/development code)

---

## Database Schema Support

The `sms_logs` table already supports real SMS integration with these fields:
- `status`: 'pending', 'sent', 'failed'
- `sent_at`: Timestamp when SMS was actually sent
- `error_message`: Error details if sending fails
- `provider_id`: SMS provider's message ID
- `provider_response`: Full API response for debugging

## Testing Your Integration

1. **Test SMS sending** from the SMS create page in the admin panel
2. **Check SMS logs** to verify status updates
3. **Monitor Laravel logs** for any errors during sending
4. **Verify phone number format** matches your provider's requirements

## Phone Number Format

Most SMS providers require international format:
- Tanzania: +255XXXXXXXXX
- Kenya: +254XXXXXXXXX
- US: +1XXXXXXXXXX

Update the phone number validation in your forms if needed.

## Error Handling

The system automatically:
- Logs failed SMS attempts
- Updates SMS status in database
- Provides error details in Laravel logs
- Continues operation even if SMS fails

## Production Checklist

- [ ] Choose and configure SMS provider
- [ ] Add credentials to .env file
- [ ] Install required SDK via Composer
- [ ] Uncomment appropriate integration code
- [ ] Remove mock/development code
- [ ] Test with real phone numbers
- [ ] Monitor SMS logs and costs
- [ ] Set up SMS provider webhooks (if available)

## Cost Considerations

- **Twilio**: ~$0.0075 per SMS
- **Africa's Talking**: ~TSh 30-50 per SMS
- **Other providers**: Varies by region and volume

Monitor your SMS usage through the admin panel's SMS logs section.
