<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FCC</title>

    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('bootstrap-datepicker-1.6.4-dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
</head>
<body style="padding: 15px 0 0 0;background: #EEF6F9;">

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"> <i class="fa fa-envelope"></i> Compose Message</h3>
                    <br/>
                    <table class="mail-table">
                        <tbody>
                        <tr>
                            <td width="90"><b>To:</b></td>
                            <td>All Federal MDAs</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="panel-body" style="background-color: #EEF6F9;">

                    {{-- Flash messages --}}
                    @if(session('success'))
                        <div style="margin-bottom: 30px;">
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div style="margin-bottom: 30px;">
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif

                    <div class="v-sep-10"></div>

                    <form id="form" method="post" action="{{ route('notification_for_all_federal_mda.send') }}"
                          class="form-horizontal">
                        @csrf

                        <div class="form-group">
                            <label for="subject" class="col-xs-2 control-label text-bold">Subject: <span class="text-danger">*</span></label>

                            <div class="col-xs-10">
                                <input type="text" name="subject" value="{{ old('subject') }}"
                                       class="form-control input-14" id="subject" placeholder="Enter Subject"
                                       autocomplete="off" maxlength="100">
                                @error('subject')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message" class="col-xs-2 control-label text-bold">Message: <span class="text-danger">*</span></label>
                            <div class="col-xs-10">
                                <textarea class="form-control input-14 counter message" rows="6" name="message" id="is-rich-text"
                                          placeholder="Enter Message" maxlength="220">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="sms_notification_message" class="col-xs-2 control-label text-bold">SMS Notification: <span class="help-block">(Optional)</span></label>

                            <div class="col-xs-10">
                                <input type="text" name="sms_notification_message" value="{{ old('sms_notification_message') }}"
                                       class="form-control input-14" id="sms_notification_message" placeholder="Enter SMS Notification Message (Optional)"
                                       autocomplete="off" maxlength="160">
                                @error('sms_notification_message')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-10 col-xs-offset-2">
                            <button type="submit" class="btn btn-info" name="btnSend" id="btnSend">
                                <i class="fa fa-send"></i> Send
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

                <div class="spinner-wrapper" style="display: none;">
                    <div class="loader">
                        <svg class="circular" viewBox="25 25 50 50">
                            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10">
                            </circle>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('bootstrap-datepicker-1.6.4-dist/js/bootstrap-datepicker.min.js') }}"></script>
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

        $('#subject').textcounter({
            type: "character",
            min: 0,
            max: 100,
            countContainerElement: "div",
            countContainerClass: "text-count-wrapper",
            countSpaces : true,
            countDown : true
        });

        $('#sms_notification_message').textcounter({
            type: "character",
            min: 0,
            max: 160,
            countContainerElement: "div",
            countContainerClass: "text-count-wrapper",
            countSpaces : true,
            countDown : true
        });

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
