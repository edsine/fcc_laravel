<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SendSmsJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /** @var array Notification payload (keys: 'sms_message' etc.) */
    protected array $notification;

    /** @var array List of phone numbers (strings) */
    protected array $phones;

    /**
     * Create a new job instance.
     *
     * @param array $notification   // e.g. ['subject'=>..., 'message'=>..., 'sms_message'=>...]
     * @param array $phones         // e.g. ['+234803....', '+23480.....', ...]
     */
    public function __construct(array $notification, array $phones)
    {
        $this->notification = $notification;
        $this->phones = $phones;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // --- SMS sending block (fixed) ---
$smsApiKey   = env('MULTITEXTER_KEY');
$smsApiUrl   = env('MULTITEXTER_URL');
$email       = env('MULTITEXTER_EMAIL');
$sender_name = env('SMS_SENDER_ID');
$forcednd    = env('MULTITEXTER_DND');
$password    = env('MULTITEXTER_PASSWORD');
$messageText = $this->notification['sms_message'] ?? $this->notification['message'] ?? '';

if (empty($smsApiUrl) || empty($email) || empty($sender_name) || empty($forcednd) || empty($password) || empty($messageText) || empty($smsApiKey)) {
    Log::error('SendNotificationJob: Missing SMS configuration.');
} else {
    // Normalize phone numbers: trim and remove empty entries
    $phones = array_values(array_filter(array_map('trim', $this->phones), fn($p) => !empty($p)));

    if (empty($phones)) {
        Log::info('SendNotificationJob: no phone numbers to send SMS to.');
    } else {
        // Build request body — recipients MUST be a CSV string for Multitexter
        $payload = [
            "email"       => $email,
            "password"    => $password,
            "message"     => $messageText,
            "sender_name" => $sender_name,
            "recipients"  => implode(',', $phones), // <-- IMPORTANT: CSV string, not array
            "forcednd"    => $forcednd,
        ];

       // Log::debug('SendNotificationJob: Testing SMS batch payload', ['payload' => $payload]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $smsApiKey,
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ])->withOptions([
                'verify'  => false, // local dev only — set CA bundle in production
                'timeout' => 30,
            ])->post($smsApiUrl, $payload);

            Log::info('SMS batch response', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            if ($response->successful()) {
                Log::info('SendNotificationJob: SMS batch sent OK', ['count' => count($phones)]);
            } else {
                Log::warning('SendNotificationJob: SMS batch failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('SendNotificationJob: SMS batch exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
    }
}
