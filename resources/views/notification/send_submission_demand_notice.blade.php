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
<body style="padding: 20px;background: #EEF6F9;">

<div class="panel panel-default">
    <div class="panel-body" style="background: #EEF6F9;">

        <h5>Send Submission Demand Notice</h5>

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
        <form id="form" method="post" action="{{ route('send_submission_demand_notice') }}" class="form-horizontal">
            @csrf

            <div class="form-group">
                <label for="submission-year" class="col-xs-2 control-label">Year:</label>
                <div class="col-xs-10">
                    <input type="text" name="submission_year" value="{{ old('submission_year', $submissionNotice->submissionYear ?? '') }}"
                           class="form-control input-14" id="submission-year"
                           placeholder="Enter Submission Year"
                           autocomplete="off" maxlength="4">
                    @error('submission_year') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="start-date" class="col-xs-2 control-label">Start Date:</label>
                <div class="col-xs-10">
                    <input type="text" name="start_date" value="{{ old('start_date', $submissionNotice->startDate ?? '') }}"
                           class="form-control input-14" id="start-date" placeholder="Enter Start Date"
                           autocomplete="off">
                    @error('start_date') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="end-date" class="col-xs-2 control-label">End Date:</label>
                <div class="col-xs-10">
                    <input type="text" name="end_date" value="{{ old('end_date', $submissionNotice->endDate ?? '') }}"
                           class="form-control input-14" id="end-date" placeholder="Enter End Date"
                           autocomplete="off">
                    @error('end_date') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="subject" class="col-xs-2 control-label">Subject:</label>
                <div class="col-xs-10">
                    <input type="text" name="subject" value="{{ old('subject', $submissionNotice->subject ?? '') }}"
                           class="form-control input-14" id="subject" placeholder="Enter Subject"
                           autocomplete="off" maxlength="100">
                    @error('subject') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="message" class="col-xs-2 control-label">Message:</label>
                <div class="col-xs-10">
                    <textarea class="form-control input-14 counter" rows="6" name="message" id="message"
                              placeholder="Enter Message" maxlength="220">{{ old('message', $submissionNotice->message ?? '') }}</textarea>
                    @error('message') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="col-xs-10 col-xs-offset-2">
                <button type="submit" class="btn btn-sm btn-info" name="btnSend" id="btnSend">
                    <i class="fa fa-send"></i> Send Notice
                </button>
            </div>

        </form>

    </div>
</div>

<div class="spinner-wrapper" style="display: none;">
    <div class="loader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10"/>
        </svg>
    </div>
</div>

<script src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>

<script src="{{ asset('bootstrap-datepicker-1.6.4-dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('js/textcounter.min.js') }}"></script>

<script>
    $(document).ready(function () {

        $('#start-date').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy', // keep d/m/Y as original
        });

        $('#end-date').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy',
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

        $('#message').textcounter({
            type: "character",
            min: 0,
            max: 220,
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
