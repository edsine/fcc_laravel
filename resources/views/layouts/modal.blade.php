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
    

    @yield('stylesheets')
    @stack('stylesheets')
</head>

<body style="padding: 15px 0 0 0;background: #EEF6F9;">

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-sm-9">
        @yield('content')
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
   
    @yield('javascripts')
    @stack('javascripts')
</body>
</html>
