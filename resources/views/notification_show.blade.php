@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="message-detail">
                @if($message)
                    <h3>Email Details</h3>
                    <p>{!! nl2br($message) !!}</p> <!-- Display the email content -->
                @else
                    <p>No email found or error fetching details.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
