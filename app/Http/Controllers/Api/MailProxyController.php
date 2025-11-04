<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendNotificationJob;
use App\Jobs\SendSMSNotificationJob;
use App\Jobs\SendSmsJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Setting;



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
        dispatch(new SendSMSNotificationJob($notification, $emails, $phones));

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
            dispatch(new SendSMSNotificationJob($notification, $emails, $phones));

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

    public function queueSMSForMDA(Request $request)
    {
        // ✅ Secure access with token
        if ($request->header('Authorization') !== 'Bearer ' . env('MAILER_API_TOKEN')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        Log::info('Received MDA mail proxy request', ['payload' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'notification' => 'required|array',
            'phones' => 'required|array',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed for MDA mail request', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $notification = $request->input('notification');
        $phones = $request->input('phones', []);

        try {
            // Dispatch the job on the mailer server
            //dispatch(new SendNotificationJob($notification, $emails, $phones));
            dispatch(new SendSmsJob($notification, $phones));

            Log::info('SendNotificationJob queued successfully for MDA mail.');

            return response()->json([
                'status' => 'queued',
                'phones_count' => count($phones),
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to queue MDA mail', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    
   
   public function getMyInbox(Request $request)
{
    $user = $request->input('user');

    if (!$user || empty($user['email'])) {
        Log::error('❌ Invalid or missing user details in request.');
        return response()->json(['error' => 'Invalid or missing user details'], 400);
    }

    $messages = [];

    try {
        Log::info('📨 Fetching inbox for user', ['email' => $user['email']]);

        // Find mail server settings by domain
        $emailDomain = substr(strrchr($user['email'], "@"), 1);
        $setting = Setting::where('hostname', 'like', '%' . $emailDomain)->first();

        if (!$setting) {
            Log::error('No IMAP settings found for domain: ' . $emailDomain);
            return response()->json(['error' => 'No IMAP settings found.'], 404);
        }

        if (empty($user['webmail_email']) || empty($user['webmail_password'])) {
            Log::error('Missing webmail credentials for user: ' . $user['email']);
            return response()->json(['error' => 'Missing webmail credentials.'], 403);
        }

        $hostname = "{{$setting->hostname}:{$setting->port}/imap/ssl}INBOX";
        $username = $user['webmail_email'];

        try {
            $password = decrypt($user['webmail_password']);
        } catch (\Throwable $e) {
            Log::error('Failed to decrypt password for user ' . $user['email'], ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to decrypt password.'], 500);
        }

        $inbox = @imap_open($hostname, $username, $password);
        if (!$inbox) {
            Log::error('IMAP connection failed: ' . imap_last_error());
            return response()->json(['error' => 'IMAP connection failed.'], 500);
        }

        // Sort and fetch the latest 50 messages
        $sorted = @imap_sort($inbox, SORTDATE, 1);
        if (!is_array($sorted) || count($sorted) === 0) {
            Log::info('No emails found for user ' . $user['email']);
            @imap_close($inbox);
            return response()->json(['messages' => []]);
        }

        $sorted = array_slice($sorted, 0, 50);

        $allowedDomain = '@federalcharacter.gov.ng';
        foreach ($sorted as $msgno) {
            $overview = @imap_fetch_overview($inbox, $msgno, 0);
            if (!$overview || !isset($overview[0])) continue;

            $ov = $overview[0];
            $fromAddress = $ov->from ?? '';

            // 🧩 FILTER: Only include messages sent from @federalcharacter.gov.ng
            if (stripos($fromAddress, $allowedDomain) === false) {
                continue;
            }

            $uid = @imap_uid($inbox, $msgno) ?: $msgno;

            $messages[] = [
                'msgno'   => (int)$msgno,
                'uid'     => (string)$uid,
                'subject' => $ov->subject ?? '(no subject)',
                'from'    => $fromAddress,
                'date'    => $ov->date ?? '',
                'seen'    => !empty($ov->seen),
            ];
        }

        @imap_close($inbox);

        Log::info('✅ Inbox fetched successfully for ' . $user['email'], [
            'filtered_count' => count($messages)
        ]);

        return response()->json(['messages' => $messages]);
    } catch (\Throwable $e) {
        Log::error('❌ Inbox fetch error', [
            'user' => $user['email'],
            'error' => $e->getMessage(),
        ]);
        return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
    }
}

    /**
     * Fetch a single message details (from request payload)
     */
    public function getMessageDetails(Request $request)
    {
        $user = $request->input('user');
        $msgId = $request->input('msgId');

        if (!$user || empty($user['email'])) {
            return response()->json(['error' => 'Invalid user details'], 400);
        }

        if (!$msgId) {
            return response()->json(['error' => 'Message ID required'], 422);
        }

        try {
            Log::info('📩 Fetching message details', ['email' => $user['email'], 'msgId' => $msgId]);

            $emailDomain = substr(strrchr($user['email'], "@"), 1);
            $setting = Setting::where('hostname', 'like', '%' . $emailDomain)->first();

            if (!$setting) {
                Log::error('No IMAP settings found for domain ' . $emailDomain);
                return response()->json(['error' => 'No IMAP settings found.'], 404);
            }

            $hostname = "{{$setting->hostname}:{$setting->port}/imap/ssl}INBOX";
            $username = $user['webmail_email'];

            $password = decrypt($user['webmail_password']);
            $inbox = @imap_open($hostname, $username, $password);

            if (!$inbox) {
                Log::error('IMAP open failed: ' . imap_last_error());
                return response()->json(['error' => 'Failed to open IMAP mailbox.'], 500);
            }

            // Try UID first
            $seqno = @imap_msgno($inbox, (int)$msgId);
            if (!$seqno) $seqno = (int)$msgId;

            $body = @imap_body($inbox, $seqno);
            if (!$body) {
                @imap_close($inbox);
                return response()->json(['error' => 'Message not found'], 404);
            }

            @imap_close($inbox);

            Log::info('✅ Message loaded successfully', ['msgId' => $msgId]);

            return response()->json(['message' => $body]);
        } catch (\Throwable $e) {
            Log::error('❌ Error fetching message details', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }



}
