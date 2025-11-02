<?php

namespace App\Http\Controllers\Notification;

use App\Constants\AppConstants;
use App\DTO\NotificationDTO;
use App\Http\Controllers\Controller;
use App\Jobs\SendSmsJob;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class SendSubmissionNotificationController extends Controller
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        //$this->middleware('auth');
        $this->notificationService = $notificationService;
    }

    /**
     * Show form (converted Blade).
     */
    public function create()
    {
        // Create empty model for view
        $submissionNotice = (object)[
            'submissionYear' => '',
            'startDate' => '',
            'endDate' => '',
            'subject' => '',
            'message' => '',
        ];

        return view('notification.send_submission_demand_notice', [
            'submissionNotice' => $submissionNotice,
        ]);
    }

    /**
     * Handle send request: validate, save notification, fetch recipients, queue SMS.
     */
    public function send(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        // validate fields (matches original required fields)
        $v = Validator::make($request->all(), [
            'submission_year' => 'required|string',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'subject' => 'required|string|max:100',
            'message' => 'required|string',
        ], [
            'submission_year.required' => 'Year is required',
            'start_date.required' => 'Invalid start date',
            'end_date.required' => 'Invalid end date',
            'subject.required' => 'Subject is required',
            'message.required' => 'Message is required',
        ]);

        if ($v->fails()) {
            Log::warning('Submission demand notice validation failed', ['errors' => $v->errors()->toArray()]);
            return redirect()->back()->withErrors($v)->withInput();
        }

        // parse dates (original used d/m/Y). If your JS gives mm/dd/yyyy, adjust accordingly.
        try {
            $startDate = Carbon::createFromFormat('d/m/Y', $request->input('start_date'));
            $endDate = Carbon::createFromFormat('d/m/Y', $request->input('end_date'));
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['start_date' => 'Invalid date format'])->withInput();
        }

        // create and persist notification record (mirrors original)
        try {
            $now = now()->format('Y-m-d H:i:s');
            $notification = new NotificationDTO();
            $notification->sender_id = $user->id;
            $notification->recipient_id_or_group = 'TO_MDA_DESK_OFFICERS';
            $notification->subject = $request->input('subject');
            $notification->message = $request->input('message');
            $notification->created = $now;
            $notification->created_by = $user->id;
            // if you use GUIDHelper earlier, you can set guid here or keep service to create it
            $notification->guid = \Str::uuid()->toString();

            $addNotificationOutcome = $this->notificationService->addNotification($notification);

            if (!$addNotificationOutcome) {
                return redirect()->back()->with('error', 'Notification could not be saved. Please try again.');
            }

            // fetch recipient contact info by roles (same roles as original)
            $roles = [
                AppConstants::PRIV_FED_MDA_UPLOAD_NOMINAL_ROLL,
                AppConstants::PRIV_STATE_MDA_UPLOAD_NOMINAL_ROLL,
            ];

            $contactInfo = $this->notificationService->getRecipientContactInfoByRole($roles);

            if (empty($contactInfo)) {
                Log::info('No contact info returned for submission demand notice.');
                return redirect()->back()->with('error', 'No recipients found.');
            }

            // collect phone numbers
            $recipientNosArray = [];
            foreach ($contactInfo as $r) {
                // $r may be array or object depending on service implementation; handle both
                $phone = is_array($r) ? ($r['primary_phone'] ?? null) : ($r->primary_phone ?? null);
                if ($phone) {
                    $recipientNosArray[] = trim($phone);
                }
            }

            if (empty($recipientNosArray)) {
                return redirect()->back()->with('error', 'No phone numbers found for recipients.');
            }

            // Build a simple notification payload (job expects the notification array + phones array)
            $notificationPayload = [
                'subject'     => $request->input('subject'),
                'message'     => $request->input('message'),
                'sms_message' => $request->input('sms_message') ?? $request->input('sms_notification_message') ?? $request->input('message'),
            ];

            // Phones array (already collected earlier as $recipientNosArray)
            $phones = $recipientNosArray; // e.g. ['+234803...', '0803...']
            
            // 🚀 Instead of dispatching locally, send to mailer API
        $mailerUrl = env('MAILER_API_URL') . '/api/queue-sms-mda';

        $response = \Illuminate\Support\Facades\Http::timeout(30)
            ->withHeaders([
                'Authorization' => 'Bearer ' . env('MAILER_API_TOKEN'),
                'Accept'        => 'application/json',
            ])
            ->post($mailerUrl, [
                'notification' => $notificationPayload,
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
            // Dispatch the job with two arguments: (notification payload, phones)
            //dispatch(new SendSmsJob($notificationPayload, $phones));


            Log::info('SMS job dispatched for submission demand notice', ['count' => count($recipientNosArray)]);

            // redirect to success like original
            return redirect()->back()->with('success', 'Submission notice queued for delivery.');
           // return redirect()->route('notification_success')->with('success', 'Submission notice queued for delivery.');
        } catch (Throwable $t) {
            Log::error('Error sending submission demand notice: ' . $t->getMessage());
            return redirect()->back()->with('error', 'Notification could not be sent at the moment, Please try again.');
        }
    }
}
