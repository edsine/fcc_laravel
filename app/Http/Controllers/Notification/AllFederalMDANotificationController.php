<?php

namespace App\Http\Controllers\Notification;

use App\Constants\AppConstants;
use App\DTO\RecipientsContactInfoDTO;
use App\Http\Controllers\Controller;
use App\Jobs\SendNotificationJob;
use App\Jobs\SendSMSNotificationJob;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class AllFederalMDANotificationController extends Controller
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
       // $this->middleware('auth');
        $this->notificationService = $notificationService;
    }

    /**
     * Show the compose form (blade view).
     */
    public function create()
    {
        return view('notification.all_federal_mda');
    }

    /**
     * Send notification to all federal MDAs.
     *
     * No validation of recipients — recipients are fetched from DB.
     */
    public function sendNowOld(Request $request)
    {
        Log::info('AllFederalMDA send request received.', ['request' => $request->all()]);

        // build notification payload from request input (basic)
        $notificationPayload = [
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'sms_message' => $request->input('sms_notification_message'),
        ];

        try {
            /** @var RecipientsContactInfoDTO $recipientsContactInfo */
            $recipientsContactInfo = $this->notificationService->getMDAContactInformationByLevelOfGovernment(AppConstants::FEDERAL);

            // recipientsEmailAddresses expected as array of ['contact_email'=>..., 'contact_name'=>...]
            $emails = [];
            if (!empty($recipientsContactInfo->recipientsEmailAddresses)) {
                foreach ($recipientsContactInfo->recipientsEmailAddresses as $e) {
                    if (!empty($e['contact_email'])) {
                        $emails[] = trim($e['contact_email']);
                    }
                }
            }

            // recipientsPhoneNumbers expected as comma-separated string
            $phones = [];
            if (!empty($recipientsContactInfo->recipientsPhoneNumbers)) {
                $phones = array_filter(array_map('trim', explode(',', $recipientsContactInfo->recipientsPhoneNumbers)));
            }

            Log::info('Fetched recipients for all federal MDA', [
                'emails_count' => count($emails),
                'phones_count' => count($phones),
            ]);

            // Dispatch same job you use elsewhere
            dispatch(new SendNotificationJob($notificationPayload, $emails, $phones));
            dispatch(new SendSMSNotificationJob($notificationPayload, $emails, $phones));

            Log::info('SendNotificationJob dispatched for all federal MDA.');

            return redirect()->back()->with('success', 'Notification queued for delivery to all Federal MDAs.');
        } catch (Throwable $t) {
            Log::error('Failed to send notification to all federal MDA: ' . $t->getMessage());
            return redirect()->back()->with('error', 'Failed to queue notification. Please try again.');
        }
    }

    public function send(Request $request)
{
    Log::info('AllFederalMDA send request received.', ['request' => $request->all()]);

    $notificationPayload = [
        'subject' => $request->input('subject'),
        'message' => $request->input('message'),
        'sms_message' => $request->input('sms_notification_message'),
    ];

    try {
        /** @var RecipientsContactInfoDTO $recipientsContactInfo */
        $recipientsContactInfo = $this->notificationService
            ->getMDAContactInformationByLevelOfGovernment(AppConstants::FEDERAL);

        // Extract emails
        $emails = [];
        if (!empty($recipientsContactInfo->recipientsEmailAddresses)) {
            foreach ($recipientsContactInfo->recipientsEmailAddresses as $e) {
                if (!empty($e['contact_email'])) {
                    $emails[] = trim($e['contact_email']);
                }
            }
        }

        // Extract phones
        $phones = [];
        if (!empty($recipientsContactInfo->recipientsPhoneNumbers)) {
            $phones = array_filter(array_map('trim', explode(',', $recipientsContactInfo->recipientsPhoneNumbers)));
        }

        Log::info('Fetched recipients for all federal MDA', [
            'emails_count' => count($emails),
            'phones_count' => count($phones),
        ]);

        // 🚀 Instead of dispatching locally, send to mailer API
        $mailerUrl = env('MAILER_API_URL') . '/api/queue-mail-mda';

        $response = \Illuminate\Support\Facades\Http::timeout(30)
            ->withHeaders([
                'Authorization' => 'Bearer ' . env('MAILER_API_TOKEN'),
                'Accept'        => 'application/json',
            ])
            ->post($mailerUrl, [
                'notification' => $notificationPayload,
                'emails'       => $emails,
                'phones'       => $phones,
            ]);

        if ($response->successful()) {
            Log::info('Mail delegation to mailer API successful', ['response' => $response->json()]);
        } else {
            Log::warning('Mail delegation failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        // 📨 Still handle SMS locally (since it works)
        //dispatch(new \App\Jobs\SendSMSNotificationJob($notificationPayload, $emails, $phones));

        return redirect()->back()->with('success', 'Notification queued for delivery to all Federal MDAs.');
    } catch (\Throwable $t) {
        Log::error('Failed to send notification to all federal MDA', ['error' => $t->getMessage()]);
        return redirect()->back()->with('error', 'Failed to queue notification. Please try again.');
    }
}

}
