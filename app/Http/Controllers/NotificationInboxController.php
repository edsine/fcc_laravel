<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

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

    // default to empty messages so blade shows "no messages" if anything goes wrong
    $messages = [];

    // basic pre-checks
    if (! $user) {
        \Log::error('Unauthenticated user attempted to access inbox.');
        return view('notification_inbox', compact('messages'));
    }

    $emailDomain = substr(strrchr($user->email, "@"), 1);
    $setting = Setting::where('hostname', 'like', '%' . $emailDomain)->first();

    if (! $setting) {
        \Log::error('No IMAP settings found for domain: ' . $emailDomain);
        return view('notification_inbox', compact('messages'));
    }

    if (empty($user->webmail_email) || empty($user->webmail_password)) {
        \Log::error('Missing webmail credentials for user: ' . $user->email);
        return view('notification_inbox', compact('messages'));
    }

    $hostname = "{{$setting->hostname}:{$setting->port}/imap/ssl}INBOX";
    $username = $user->webmail_email;

    try {
        // decrypt password safely
        try {
            $password = decrypt($user->webmail_password);
        } catch (\Throwable $t) {
            \Log::error('Failed to decrypt webmail password for user '.$user->email .': '.$t->getMessage());
            return view('notification_inbox', compact('messages'));
        }

        // open IMAP
        $inbox = @imap_open($hostname, $username, $password);
        if (! $inbox) {
            \Log::error('IMAP connection failed: ' . imap_last_error(), ['hostname' => $hostname, 'user' => $username]);
            return view('notification_inbox', compact('messages'));
        }

        \Log::info('IMAP connection successful for user: '.$user->email);

        // make sure INBOX accessible
        $folder = @imap_check($inbox);
        if (! $folder) {
            \Log::error('Failed to access INBOX folder for user: '.$user->email);
            @imap_close($inbox);
            return view('notification_inbox', compact('messages'));
        }

        // sort messages descending by date
        $sorted = @imap_sort($inbox, SORTDATE, 1);
        if (! $sorted || !is_array($sorted) || count($sorted) === 0) {
            \Log::info('No emails found after sorting for user: '.$user->email);
            @imap_close($inbox);
            return view('notification_inbox', compact('messages'));
        }

        // (OPTION) if you want to limit number of messages fetched for performance, you can slice here.
        // e.g. $sorted = array_slice($sorted, 0, 100);
        // Current requirement: load all, so we do not slice.
         // Fetch the first 30 emails
            $sorted = array_slice($sorted, 0, 30);

        foreach ($sorted as $msgno) {
            try {
                $overview = @imap_fetch_overview($inbox, $msgno, 0);
                if (! $overview || ! isset($overview[0])) {
                    \Log::warning("No overview for message #{$msgno} (user: {$user->email}).");
                    continue;
                }

                $ov = $overview[0];

                // resolve UID
                $uid = @imap_uid($inbox, $msgno);
                if ($uid === false) {
                    // fallback to msgno string if uid cannot be obtained
                    $uid = (string)$msgno;
                }

                // seen flag detection (try available properties)
                $isSeen = false;
                if (isset($ov->seen)) {
                    $isSeen = (bool)$ov->seen;
                } elseif (isset($ov->flags) && is_string($ov->flags)) {
                    $isSeen = (stripos($ov->flags, '\\Seen') !== false);
                } elseif (isset($ov->recent) && isset($ov->unseen)) {
                    // some servers expose different props
                    $isSeen = empty($ov->recent) && empty($ov->unseen);
                }

                $messages[] = [
                    'msgno'   => (int)$msgno,
                    'uid'     => (string)$uid,
                    'subject' => isset($ov->subject) ? $ov->subject : '(no subject)',
                    'from'    => isset($ov->from) ? $ov->from : '(unknown sender)',
                    'date'    => isset($ov->date) ? $ov->date : '',
                    'seen'    => $isSeen,
                ];
            } catch (\Throwable $inner) {
                \Log::warning('Failed to fetch overview for message '.$msgno.' for user '.$user->email.': '.$inner->getMessage());
                // continue to next message (do not add empty items)
                continue;
            }
        }

        @imap_close($inbox);

    } catch (\Throwable $e) {
        // log full exception for debugging but do not surface to user; blade will show "no messages"
        \Log::error('IMAP Error while loading inbox for user '.$user->email.': '.$e->getMessage(), [
            'trace' => $e->getTraceAsString(),
        ]);
        // keep $messages as empty array so blade shows fallback "no messages"
    }

    return view('notification_inbox', compact('messages'));
}



 // Show the email details (supports UID or sequence number)
public function showNotification(Request $request, $msgId)
{
    $user = Auth::user();
    $emailDomain = substr(strrchr($user->email, "@"), 1);
    $setting = Setting::where('hostname', 'like', '%' . $emailDomain)->first();

    if (!$setting) {
        \Log::error('No settings found for domain: ' . $emailDomain);
        return response('No IMAP settings found.', 404);
    }

    if (empty($user->webmail_email) || empty($user->webmail_password)) {
        \Log::error('Missing webmail credentials for user: ' . $user->email);
        return response('Missing webmail credentials.', 403);
    }

    $hostname = "{{$setting->hostname}:{$setting->port}/imap/ssl}INBOX";
    $username = $user->webmail_email;

    try {
        $password = decrypt($user->webmail_password);
    } catch (\Throwable $e) {
        \Log::error('Failed to decrypt webmail password for user: ' . $user->email);
        return response('Failed to load message.', 500);
    }

    try {
        $inbox = @imap_open($hostname, $username, $password);
        if (!$inbox) {
            $err = imap_last_error();
            \Log::error('IMAP open failed: ' . $err, ['hostname' => $hostname, 'user' => $username]);
            return response('Failed to open IMAP mailbox.', 500);
        }

        // Normalize incoming id (could be uid or seqno)
        $incoming = (string)$msgId;
        $incomingInt = (int)$incoming;

        // Try treating incoming as a UID and map to sequence number
        $seqno = @imap_msgno($inbox, $incomingInt);
        if ($seqno === 0 || $seqno === false) {
            // Not a UID, maybe it's already a seqno — check bounds
            $numMsg = imap_num_msg($inbox);
            if ($incomingInt > 0 && $incomingInt <= $numMsg) {
                $seqno = $incomingInt;
            } else {
                \Log::warning("Invalid message identifier requested: {$incoming} for user {$user->email}. numMsg={$numMsg}");
                @imap_close($inbox);
                return response('No email found or error fetching details.', 404);
            }
        } else {
            \Log::debug("Mapped UID {$incomingInt} to sequence #{$seqno} for user {$user->email}");
        }

        // Double-check message exists by fetching overview
        $overview = @imap_fetch_overview($inbox, $seqno, 0);
        if (! $overview || ! isset($overview[0])) {
            \Log::warning("No overview for message seq {$seqno} (incoming: {$incoming}) for user {$user->email}");
            @imap_close($inbox);
            return response('No email found or error fetching details.', 404);
        }

        // Fetch structure and find best part (reuse your part-finding + decoding logic)
        $structure = @imap_fetchstructure($inbox, $seqno);
        if (! $structure) {
            // fallback: try simple body fetch
            $raw = @imap_fetchbody($inbox, $seqno, 1);
            @imap_close($inbox);
            if ($request->ajax()) {
                return nl2br(e($raw ?: ''));
            }
            $message = $raw ?: '';
            return view('notification_show', compact('message'));
        }

        // find part number (prefer text/plain then text/html)
        $partNumber = null;
        $isHtml = false;

        $findPart = function ($part, $prefix = '') use (&$findPart, &$partNumber, &$isHtml) {
            if (isset($part->type) && (int)$part->type === 0) {
                $subtype = isset($part->subtype) ? strtoupper($part->subtype) : '';
                if ($subtype === 'PLAIN' && $partNumber === null) {
                    $partNumber = $prefix === '' ? '1' : $prefix;
                    $isHtml = false;
                } elseif ($subtype === 'HTML') {
                    if ($partNumber === null) {
                        $partNumber = $prefix === '' ? '1' : $prefix;
                        $isHtml = true;
                    }
                }
            }
            if (isset($part->parts) && is_array($part->parts)) {
                foreach ($part->parts as $index => $subpart) {
                    $nextPrefix = $prefix === '' ? (string)($index + 1) : $prefix . '.' . ($index + 1);
                    $findPart($subpart, $nextPrefix);
                }
            }
        };

        if (! isset($structure->parts)) {
            $partNumber = '1';
            $isHtml = (isset($structure->subtype) && strtoupper($structure->subtype) === 'HTML');
        } else {
            $findPart($structure, '');
            if ($partNumber === null) {
                $partNumber = '1';
                $isHtml = false;
            }
        }

        // fetch the chosen part (using sequence number)
        $raw = @imap_fetchbody($inbox, $seqno, $partNumber);

        if ($raw === '' || $raw === false) {
            $raw = @imap_fetchbody($inbox, $seqno, 1);
            if ($raw === '' || $raw === false) {
                $raw = @imap_body($inbox, $seqno);
            }
        }

        // determine encoding for the chosen part
        $getPartObject = function ($structure, $partNo) {
            if ($partNo === '1' && !isset($structure->parts)) {
                return $structure;
            }
            $indices = explode('.', $partNo);
            $obj = $structure;
            foreach ($indices as $idx) {
                $pos = ((int)$idx) - 1;
                if (!isset($obj->parts[$pos])) {
                    return null;
                }
                $obj = $obj->parts[$pos];
            }
            return $obj;
        };

        $partObj = $getPartObject($structure, $partNumber);
        $encoding = $partObj->encoding ?? ($structure->encoding ?? 0);

        switch ((int)$encoding) {
            case 3:
                $decoded = base64_decode($raw);
                break;
            case 4:
                $decoded = quoted_printable_decode($raw);
                break;
            default:
                $decoded = $raw;
                break;
        }

        // prepare output
        if ($isHtml) {
            $messageContent = $decoded;
        } else {
            $messageContent = nl2br(e($decoded));
        }

        @imap_close($inbox);

        if ($request->ajax()) {
            return $isHtml ? $messageContent : $messageContent;
        }

        $message = $isHtml ? $messageContent : $decoded;
        return view('notification_show', compact('message'));
    } catch (\Throwable $e) {
        \Log::error('IMAP fetch error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        return response('No email found or error fetching details.', 500);
    }
}





}
