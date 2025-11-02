<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendNotificationJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;



class MailProxyController extends Controller
{
    public function send(Request $request)
    {
        // ✅ Secure access with token
        if ($request->header('Authorization') !== 'Bearer ' . env('MAILER_API_TOKEN')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        Log::info('Received mail proxy request', ['payload' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'notification' => 'required|array',
            'emails' => 'required|array',
            'phones' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $notification = $request->notification;
        $emails = $request->emails;
        $phones = $request->phones ?? [];

        // Queue email job
        dispatch(new SendNotificationJob($notification, $emails, $phones));

        Log::info('Mail job queued successfully on mailer server.');

        return response()->json(['status' => 'queued'], 200);
    }

  
    public function queueMailForMDA(Request $request)
    {
        // ✅ Secure access with token
        if ($request->header('Authorization') !== 'Bearer ' . env('MAILER_API_TOKEN')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        Log::info('Received MDA mail proxy request', ['payload' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'notification' => 'required|array',
            'emails' => 'required|array',
            'phones' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed for MDA mail request', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $notification = $request->input('notification');
        $emails = $request->input('emails');
        $phones = $request->input('phones', []);

        try {
            // Dispatch the job on the mailer server
            dispatch(new SendNotificationJob($notification, $emails, $phones));

            Log::info('SendNotificationJob queued successfully for MDA mail.');

            return response()->json([
                'status' => 'queued',
                'emails_count' => count($emails),
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to queue MDA mail', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
