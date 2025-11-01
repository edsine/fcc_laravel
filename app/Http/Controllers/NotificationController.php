<?php

namespace App\Http\Controllers;

use App\Jobs\SendNotificationJob;
use App\Jobs\SendSMSNotificationJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function create()
    {
        return view('notification.open');
    }

    
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

}

