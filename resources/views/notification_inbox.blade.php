@extends('layouts.app')

@section('page_toolbar_left')
Mailbox
@endsection
@section('content')
@push('stylesheets')
  <link rel="stylesheet" href="{{ asset('fancybox/jquery.fancybox.css') }}" type="text/css" media="screen"/>
    <link rel="stylesheet" href="{{ asset('css/inbox.css') }}" type="text/css" media="screen"/>  
@endpush
<style>
.inbox-table > tbody > tr > td.inbox-menu {
    /* border-right: 1px solid #a6c4f1; */
    width: 200px;
    /* background-color: #F5F9FB; */
    height: 750px;
}
.message-list-item.selected {
    background-color: #e7f1ff;
    border-left: 3px solid #007bff;
}
.spinner {
  margin: 0 auto;
  width: 40px;
  height: 40px;
  border: 4px solid rgba(0, 123, 255, 0.2); /* light ring */
  border-top-color: #007bff; /* active blue part */
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

/* Simple rotation animation */
@keyframes spin {
  to { transform: rotate(360deg); }
}

</style>
    <div class="row">
        <div class="col-md-12">
            <div class="inbox-wrapper">
                <table class="inbox-table">
                    <tbody>
                        <tr>
                            <td valign="top" class="inbox-menu">
                                <div class="list-group">
                                    <a href="{{ route('notification_inbox') }}" class="list-group-item active">
                                        <i class="fa fa-envelope"></i> Inbox
                                    </a>
                                    <a href="#" class="list-group-item disabled" disabled>
                                    <i class="fa fa-send"></i> Sent
                                </a>
                                <a href="#" class="list-group-item disabled" disabled>
                                    <i class="fa fa-trash"></i> Trash
                                </a>
                                <a href="#" class="list-group-item disabled" disabled>
                                    <i class="fa fa-edit"></i> Draft
                                </a>
                                </div>
                                <div class="v-sep-20"></div>
                            <div class="v-sep-20"></div>

                            
                                <div class="text-center">
                                    <div class="btn-group" style="width: 90%;">
                                        <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false"
                                                style="width: 100%">
                                            Compose <span class="caret"></span>
                                        </button>
                                        <div class="v-sep-5"></div>
                                        <ul class="dropdown-menu">
                                           <li>
                                                <a href="#"
                                                   onclick="openFancyBox2('{{ route('notification_for_all_federal_mda.create') }}');">
                                                    Send Message To All Federal MDAs
                                                </a>
                                            </li>

                                            <li role="separator" class="divider"></li>

                                            <li>
                                                <a href="#"
                                                   onclick="openFancyBox2('{{ route('open_notification') }}');">
                                                    Send Message To Any Email(s)/Phone Number(s)
                                                </a>
                                            </li>

                                            <li role="separator" class="divider"></li>

                                            <li>
                                                <a href="#"
                                                   onclick="openFancyBox('{{ route('send_submission_demand_notice.create') }}');">
                                                    Send Submission Demand Notice
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                            <td valign="top" class="message-list">
                                <div class="message-scroll-box" style="height: 750px; overflow-y: scroll;">
                                    <div id="message-list-wrapper" style="padding: 10px;">
                                        @forelse ($messages as $message)
<div class="message-list-item" style="cursor: pointer;" onclick="loadEmailMessage('{{ $message['uid'] }}', this)">
        <div class="message-list-item-content-wrapper" style="margin-bottom: 15px;">
            <div class="message-list-item-avatar">
                <img style="height: 45px;" src="{{ asset('images/user-128px.png') }}" />
            </div>
            <div class="message-list-item-summary">
                <div class="sender-date">
                    <div class="summary-sender">
                        {{ $message['from'] }} <br />
                        {{ $message['subject'] }}
                    </div>
                    <div class="summary-date">
                        {{ \Carbon\Carbon::parse($message['date'])->format('Y-m-d H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
                                        @empty
                                            <div class="empty-message-list-notice">Your inbox is empty</div>
                                        @endforelse
                                    </div>
                                </div>
                            </td>

                            <td valign="top" class="message-detail" style="width: 100%; padding: 15px;">
    <div id="email-loading" class="text-center" style="display:none; padding: 40px 0;">
    <div class="spinner"></div>
    <p style="margin-top:10px; font-weight:500;">Loading email content...</p>
</div>
    <div id="email-content">
        <p>Select a message to view details</p>
    </div>
</td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>



<!-- global loading overlay -->
<div id="loading-overlayed" style="display:none;position:fixed;inset:0;z-index:10500;
     background:rgba(0,0,0,0.35);align-items:center;justify-content:center;">
    <div style="text-align:center;color:#fff;">
        <div class="spinner-border" role="status" aria-hidden="true" style="width:3rem;height:3rem"></div>
        <div style="margin-top:8px;font-weight:600">Loading…</div>
    </div>
</div>
@push('javascripts')
<script type="text/javascript" src="{{ asset('fancybox/jquery.fancybox.js') }}"></script>
    <script type="text/javascript" src="{{ asset('jquery.nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script>
function loadEmailMessage(uid, el /* the clicked DOM element (this) */) {
    try {
        // show the full-page overlay spinner
        $('#loading-overlay').fadeIn(100);

        // Also show the small 'loading' message in the email pane
        // document.getElementById('loading-overlayed').style.display = 'block';
        document.getElementById('email-loading').style.display = 'block';
        document.getElementById('email-content').style.display = 'none';
        document.getElementById('email-content').innerHTML = '';

        // Visual feedback: remove selected from all then add to clicked
        const allMessages = document.querySelectorAll('.message-list-item');
        allMessages.forEach(node => node.classList.remove('selected'));
        if (el) {
            el.classList.add('selected');
            // disable pointer events to prevent double-clicks while loading
            el.style.pointerEvents = 'none';
        }

        // Fetch message HTML
        fetch(`{{ route('notification_show', ':uid') }}`.replace(':uid', uid), {
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                 $('#loading-overlay').fadeOut(100);
                throw new Error('Failed to fetch message (status ' + response.status + ')');
            }
            return response.text();
             $('#loading-overlay').fadeOut(100);
        })
        .then(html => {
             $('#loading-overlay').fadeOut(100);
            document.getElementById('email-content').innerHTML = html;
            document.getElementById('email-content').style.display = 'block';
        })
        .catch(error => {
             $('#loading-overlay').fadeOut(100);
            console.error('loadEmailMessage error:', error);
            document.getElementById('email-content').innerHTML = '<p class="text-danger">Failed to load message.</p>';
            document.getElementById('email-content').style.display = 'block';
        })
        .finally(() => {
            // always hide overlay and re-enable item
            $('#loading-overlay').fadeOut(100);
            document.getElementById('email-loading').style.display = 'none';
            if (el) el.style.pointerEvents = '';
        });
    } catch (err) {
        // defensive fallback if something unexpected happens
        console.error('Unexpected error in loadEmailMessage:', err);
        $('#loading-overlay').fadeOut(100);
        document.getElementById('email-loading').style.display = 'none';
        document.getElementById('email-content').innerHTML = '<p class="text-danger">Failed to load message.</p>';
        document.getElementById('email-content').style.display = 'block';
        if (el) el.style.pointerEvents = '';
    }
}
</script>
    <script>


        $(document).ready(function ($) {

    $(".message-scroll-box").niceScroll("#message-list-wrapper", {cursorcolor: "#3F51B5"});
});


        /*function resizeIframe(obj) {
            obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
        }*/

        function openFancyBox(url) {
            $.fancybox.open({
                autoSize: false,
                width: '1024',
                height: '640',
                href: '' + url + '',
                type: 'iframe',
                padding: 2,
                helpers: {
                    overlay: {
                        closeClick: false
                    }
                }
            });
            return false;
        }

        function openFancyBox2(url) {
            $.fancybox.open({
                autoSize: false,
                width: '85%',
                height: '98%',
                href: '' + url + '',
                type: 'iframe',
                padding: 2,
                helpers: {
                    overlay: {
                        closeClick: false
                    }
                }
            });
            return false;
        }

    </script>
   

@endpush
@endsection
