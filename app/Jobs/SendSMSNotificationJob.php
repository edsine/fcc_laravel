<?php

namespace App\Jobs;

use App\Mail\SendEmailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendSMSNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $notification;
    public array $emails;
    public array $phones;

    public $tries = 3;

    public function __construct(array $notification, array $emails = [], array $phones = [])
    {
        $this->notification = $notification;
        $this->emails = $emails;
        $this->phones = $phones;
    }

    public function handle()
    {
        Log::info('SendNotificationJob started.', [
            'emails' => $this->emails,
            'phones' => $this->phones,
        ]);

        /* // ✉️ Send emails (errors are logged but do not stop the job)
        foreach ($this->emails as $email) {
            try {
                Log::debug("Sending email to: $email");
                Mail::to($email)->send(new SendEmailNotification(
                    $this->notification['subject'] ?? '',
                    $this->notification['message'] ?? ''
                ));
                Log::info("Email successfully sent to: $email");
            } catch (\Throwable $e) {
                Log::error("Email sending failed to $email", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                // 🚀 Re-throw so Laravel will retry the job
                //throw $e;
            }
        } */

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

        $payload1 = [
            "email"       => $email,
            "message"     => $messageText,
            "sender_name" => $sender_name,
            "recipients"  => implode(',', $phones), // <-- IMPORTANT: CSV string, not array
            "forcednd"    => $forcednd,
        ];

        Log::debug('SendNotificationJob: SMS batch payload started.', ['payload' => $payload1]);

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
            // 🚀 Re-throw so Laravel will retry the job
                throw $e;
        }
    }
}




        Log::info('SendSMSNotificationJob completed.');
    }
}
