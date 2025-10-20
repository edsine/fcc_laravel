<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <title>@yield('title', 'Federal Character Commission')</title>

    <link href="{{ asset('font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('login-v3/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('login-v3/css/login.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/loading.css') }}" rel="stylesheet">

    <script src="{{ asset('login-v3/js/modernizr.min.js') }}"></script>

    @yield('stylesheets')
</head>

<body class="body-bg-full profile-page">

    <div id="wrapper" class="wrapper">
        <div class="row container-min-full-height">
            <div class="col-lg-8 p-3 login-left">
                <div class="w-50">
                    @yield('main')
                </div>
            </div>
            <div class="col-lg-4 login-right d-lg-flex d-none pos-fixed pos-right text-inverse container-min-full-height">
                <div class="login-content px-3 w-75 text-center">

                    <div>
                        <img src="{{ asset('images/fcc-logo.png') }}" class="login-info-logo"/>
                    </div>

                    <h4 class="mb-4 text-center fw-300">Federal Character Commission</h4>
                    <p class="heading-font-family fw-300 letter-spacing-minus">
                        {{ 'Data Processing & Analysis Platform' }}
                    </p>
                    <div class="fw-600 ripple pd-lr-60 mr-t-150"></div>

                    <ul class="list-inline mt-4 heading-font-family text-uppercase fs-13 mr-t-20">
                        <li class="list-inline-item">
                            <a href="http://federalcharacter.gov.ng" target="_blank">FCC Website</a>
                        </li>
                        <li class="list-inline-item">
                            <a href="http://federalcharacter.gov.ng/training" target="_blank">Portal Training</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="loading-overlay">
        <div class="spinner-wrapper">
            <div class="loader">
                <svg class="circular" viewBox="25 25 50 50">
                    <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10"></circle>
                </svg>
            </div>
        </div>
    </div>

    <script src="{{ asset('login-v3/js/jquery.min.js') }}"></script>
    <script src="{{ asset('login-v3/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('login-v3/js/material-design.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    @yield('javascripts')
</body>
</html>
