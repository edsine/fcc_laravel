<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <title>Federal Character Commission @yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-override.css') }}" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="{{ asset('font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/font-awesome-animation.min.css') }}" rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/loading.css') }}" rel="stylesheet">
    
</head>

<body style="padding: 15px 0 0 0;background: #EEF6F9;">

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-sm-9">
    <div class="card shadow">
        <div class="card-header bg-primary text-white" style="padding: 10px;">
            <i class="fa fa-envelope"></i> Send Notification
        </div>
        <div class="card-body" style="margin-top: 20px;">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form id="form" method="POST" action="{{ route('send_notification') }}">
                @csrf

                {{-- Email Addresses --}}
                <div class="form-group">
                    <label for="email_addresses"><i class="fa fa-envelope"></i> Email Addresses <span class="text-danger">*</span></label>
                    <input type="text" name="email_addresses" id="email_addresses" class="form-control" value="{{ old('email_addresses') }}" placeholder="Enter Emails (comma separated)">
                    @error('email_addresses') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Phone Numbers --}}
                <div class="form-group">
                    <label for="phone_numbers"><i class="fa fa-phone"></i> Phone Numbers</label>
                    <input type="text" name="phone_numbers" id="phone_numbers" class="form-control" value="{{ old('phone_numbers') }}" placeholder="Enter Phones (comma separated)">
                </div>

                {{-- Subject --}}
                <div class="form-group">
                    <label for="subject"><i class="fa fa-header"></i> Subject <span class="text-danger">*</span></label>
                    <input type="text" name="subject" id="subject" class="form-control" value="{{ old('subject') }}" placeholder="Subject">
                    @error('subject') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Message --}}
                <div class="form-group">
                    <label for="message"><i class="fa fa-envelope"></i> Message <span class="text-danger">*</span></label>
                    <textarea class="form-control input-14 counter message" rows="6" name="message" id="is-rich-text"
                              placeholder="Enter your message here..." maxlength="220">{{ old('message') }}</textarea>
                    @error('message') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- SMS Notification Message --}}
                <div class="form-group">
                    <label for="sms_notification_message"><i class="fa fa-sms"></i> SMS Notification Message</label>
                    <input type="text" name="sms_notification_message" id="sms_notification_message" class="form-control" value="{{ old('sms_notification_message') }}" placeholder="Optional SMS Message (160 characters)">
                    @error('sms_notification_message') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Submit --}}
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-success" id="btnSend">
                        <i class="fa fa-paper-plane"></i> Send
                    </button>
                </div>
            </form>
        </div>
    </div>

   
 </div>
     
        <div class="col-sm-3">

            <div style="padding: 10px;text-align: center;">
                <div style="margin-bottom: 100px;">
                    <img src="{{ asset('images/email-send.png') }}"/>
                    
                </div>
<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
 @if(!session('success'))
                <div class="spinner-wrapper" style="display: none;">
                    <div class="loader">
                        <svg class="circular" viewBox="25 25 50 50">
                            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3"
                                    stroke-miterlimit="10">
                            </circle>
                        </svg>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
    
 <!-- JS -->
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
     
<script src="{{ asset('js/textcounter.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('tinymce_4.5.3/tinymce/js/tinymce/tinymce.min.js') }}"></script>

<script>
    $(document).ready(function () {

        tinymce.init({
            selector: 'textarea#is-rich-text',
            height: 340,
            menubar: false,
            plugins: [
                'lists'
            ],
            toolbar: 'undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist'
        });

        $('#subject').textcounter(
            {
                type: "character",
                min: 0,
                max: 100,
                countContainerElement: "div",
                countContainerClass: "text-count-wrapper",
                countSpaces: true,
                countDown: true
            }
        );

        /*$('.message').textcounter(
         {
         type: "character",
         min: 0,
         max: 220,
         countContainerElement: "div",
         countContainerClass: "text-count-wrapper",
         countSpaces : true,
         countDown : true
         }
         );*/

        $('#sms_notification_message').textcounter(
            {
                type: "character",
                min: 0,
                max: 160,
                countContainerElement: "div",
                countContainerClass: "text-count-wrapper",
                countSpaces: true,
                countDown: true
            }
        );


        $('#btnSend').on('click', function () {
    $(this).attr('disabled', true);             // Disable the button
    $(this).text('Sending...');                 // Update button text
    $('.spinner-wrapper').show();               // Show the spinner
    $('#form').submit();
});


    });
</script>
</body>
</html>