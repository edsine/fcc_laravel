<?php

namespace App\Http\Controllers;

use App\Jobs\SendNotificationJob;
use App\Jobs\SendSMSNotificationJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class NotificationController extends Controller
{
    public function create()
    {
        return view('notification.open');
    }

    
   /* public function send(Request $request) 
{
    Log::info('Notification send request received.', [
        'request' => $request->all(),
    ]);

    $validator = Validator::make($request->all(), [
        'email_addresses' => 'required|string',
        'subject' => 'required|string|max:100',
        'message' => 'required|string',
        'phone_numbers' => 'nullable|string',
        'sms_notification_message' => 'nullable|string|max:160',
    ]);

    if ($validator->fails()) {
        Log::warning('Validation failed.', [
            'errors' => $validator->errors()->toArray(),
        ]);
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $emails = array_filter(array_map('trim', explode(',', $request->email_addresses)));
    $phones = array_filter(array_map('trim', explode(',', $request->phone_numbers ?? '')));

    Log::info('Parsed recipients', [
        'emails' => $emails,
        'phones' => $phones,
    ]);

    $notification = [
        'subject' => $request->subject,
        'message' => $request->message,
        'sms_message' => $request->sms_notification_message,
    ];

    dispatch(new SendNotificationJob($notification, $emails, $phones));
    dispatch(new SendSMSNotificationJob($notification, $emails, $phones));

    Log::info('Notification job dispatched.');

    return redirect()->back()->with('success', 'Notification queued for delivery.');
}
 */



public function send(Request $request)
{
    Log::info('Notification send request received.', [
        'request' => $request->all(),
    ]);

    $validator = Validator::make($request->all(), [
        'email_addresses' => 'required|string',
        'subject' => 'required|string|max:100',
        'message' => 'required|string',
        'phone_numbers' => 'nullable|string',
        'sms_notification_message' => 'nullable|string|max:160',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }


    $emails = array_filter(array_map('trim', explode(',', $request->email_addresses)));
    $phones = array_filter(array_map('trim', explode(',', $request->phone_numbers ?? '')));

    $notification = [
        'subject' => $request->subject,
        'message' => $request->message,
        'sms_message' => $request->sms_notification_message,
    ];

    // 📨 Send to the mailer API instead of local queue
    try {
        $response = Http::timeout(15)
            ->withHeaders([
                'Authorization' => 'Bearer ' . env('MAILER_API_TOKEN'),
                'Accept' => 'application/json',
            ])
            ->post(env('MAILER_API_URL') . '/api/queue-mail', [
                'notification' => $notification,
                'emails' => $emails,
                'phones' => $phones,
            ]);

        if ($response->successful()) {
            Log::info('Mail delegation successful', ['response' => $response->json()]);
        } else {
            Log::warning('Mail delegation failed', ['response' => $response->body()]);
        }
    } catch (\Throwable $e) {
        Log::error('Error delegating mail request', ['error' => $e->getMessage()]);
    }

    // 📨 SMS still works locally, keep as-is
    dispatch(new SendSMSNotificationJob($notification, $emails, $phones));

    return redirect()->back()->with('success', 'Notification queued for delivery.');
}

}

