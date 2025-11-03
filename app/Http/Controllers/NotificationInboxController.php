<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class NotificationInboxController extends Controller
{
    public function __construct()
    {
        // Ensure user is logged in
        // $this->middleware('auth');
    }

    // Show the inbox
public function showInbox(Request $request)
    {
        $user = Auth::user();
        $messages = [];

        if (! $user) {
            Log::error('Unauthenticated user attempted to access inbox.');
            return view('notification_inbox', compact('messages'));
        }

        try {
            $payload = [
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'webmail_email' => $user->webmail_email,
                    'webmail_password' => $user->webmail_password,
                ],
            ];

            $response = Http::timeout(20)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . env('MAILER_API_TOKEN'),
                    'Accept' => 'application/json',
                ])
                ->post(env('MAILER_API_URL') . '/api/get-my-inbox', $payload);

            if ($response->successful()) {
                $messages = $response->json()['messages'] ?? [];
                return view('notification_inbox', compact('messages'));
            }

            Log::warning('Failed to load inbox from proxy', ['status' => $response->status()]);
        } catch (\Throwable $e) {
            Log::error('Proxy inbox request failed', ['error' => $e->getMessage()]);
        }

        return view('notification_inbox', compact('messages'));
    }

    // Show single message details
    public function showNotification(Request $request, $msgId)
    {
        $user = Auth::user();

        if (! $user) {
            return response('Unauthenticated', 401);
        }

        try {
            $payload = [
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'webmail_email' => $user->webmail_email,
                    'webmail_password' => $user->webmail_password,
                ],
                'msgId' => $msgId,
            ];

            $response = Http::timeout(20)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . env('MAILER_API_TOKEN'),
                    'Accept' => 'application/json',
                ])
                ->post(env('MAILER_API_URL') . '/api/get-message-details', $payload);

            if ($response->successful()) {
                $message = $response->json()['message'] ?? '';
                return view('notification_show', compact('message'));
            }

            Log::warning('Failed to load message from proxy', ['status' => $response->status()]);
        } catch (\Throwable $e) {
            Log::error('Proxy message request failed', ['error' => $e->getMessage()]);
        }

        return response('Failed to fetch message', 500);
    }





}
