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
    <link href="{{ asset('select2-4.0.3/dist/css/select2.min.css') }}" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="{{ asset('font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/font-awesome-animation.min.css') }}" rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/loading.css') }}" rel="stylesheet">

    @yield('stylesheets')
    @stack('stylesheets')
    <style>
        a.disabled {
  pointer-events: none;   /* Prevent clicks */
  cursor: not-allowed;    /* Show "disabled" cursor */
  opacity: 0.6;           /* Make it look dimmed */
  text-decoration: none;
}
 html, body {
      height: 100%;
    }
    .page-wrapper {
      min-height: 100%;
      display: flex;
      flex-direction: column;
    }
    .content {
      flex: 1;
    }

    </style>
</head>

<body>
    <div class="page-wrapper">
    @section('header')
        <div class="fixed-header do-not-print">
            @php
                $loggedInUser = Auth::user();
                $privilegeChecker = true;//$loggedInUser?->privilegeChecker;
            @endphp

            <nav id="navigation-1" class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                                aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">
                            <img src="{{ asset('images/logo-inner.png') }}" alt="Logo"/>
                        </a>
                    </div>

                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-right user-nav">
                            <li>
                                <a href="#" class="user-organization"
                                   style="text-transform:uppercase; max-width: 350px; height: 54px; font-size: 11px;line-height: normal;display: table-cell; vertical-align: middle;">
                                    {{ Auth::user()->organizationName ?? 'Federal Character Commission' }}
                                </a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-haspopup="true" aria-expanded="false" disabled>
                                    @if (empty($loggedInUser?->profile_picture_file_name))
                                        <img src="{{ asset('images/user.png') }}" class="user-icon"/>
                                    @else
                                        <img src="{{ $loggedInUser->profile_picture_file_name }}" class="profile-icon-32"/>
                                    @endif

                                    {{ Auth::user()->first_name .' '.Auth::user()->last_name ?? 'User' }} <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    
                                     <li><a href="{{ route('profile.show') }}">View Profile</a></li>
                                     <li role="separator" class="divider no-margin"></li>
                                     <li><a href="{{ route('profile.edit') }}">Edit Profile</a></li>
                                    
                                     <li role="separator" class="divider no-margin"></li>
                                     @if (!empty(auth()->user()) && auth()->user()->id == 1)
                                         <li><a href="{{ route('settings.index') }}">Settings</a></li>
                                         <li role="separator" class="divider no-margin"></li>
                                     @endif
                                    <li><a href="{{ route('logout') }}">Log Out</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            {{-- SECONDARY NAVBAR --}}
            <nav id="navigation-2" class="navbar navbar-default navbar-fixed-top_">
                <div class="container-fluid" style="padding-left: 0;">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar2"
                                aria-expanded="false" aria-controls="navbar2">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>

                    <div id="navbar2" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            {{-- Example privilege-based conditions --}}
                            {{-- @if ($privilegeChecker?->appUser) --}}
                                <li><a href="{{ url('dashboard') }}">Home</a></li>
                            {{-- @endif --}}

                            {{-- @if ($privilegeChecker?->misHead) --}}
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle disabled disabled" data-toggle="dropdown" role="button"
                                       aria-haspopup="true" aria-expanded="false" >
                                       Submissions <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{ url('mis_head_nominal_roll_pending_approval_list') }}">Submissions</a></li>
                                        <li><a href="{{ url('submission_status') }}">Submission Status</a></li>
                                        <li><a href="{{ url('mis_head_submission_permission_request_pending_approval_list') }}">
                                            Nominal Roll Submission Permission Requests
                                        </a></li>
                                        <li><a href="{{ url('mis_head_fix_submission_issues') }}">Fix Submission/Processing Issues</a></li>
                                    </ul>
                                </li>
                            {{-- @endif --}}

                            {{-- 1. Establishments --}}
{{-- @if ($privilegeChecker?->superAdmin || $privilegeChecker?->misHead) --}}
    <li class="dropdown">
        <a href="#" class="dropdown-toggle disabled" data-toggle="dropdown" role="button"
           aria-haspopup="true" aria-expanded="false" disabled>
            Establishments <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li class="dropdown-header">FCC</li>
            <li><a href="{{ url('committee_list') }}">FCC Committee</a></li>
            <li><a href="{{ url('merge_committee') }}">Merge Committees</a></li>
            <li role="separator" class="divider"></li>
            <li class="dropdown-header">FEDERAL</li>
            <li><a href="{{ url('federal_ministry_list') }}">Federal Ministry</a></li>
            <li><a href="{{ url('federal_parastatal_list') }}">Federal Parastatal</a></li>
            <li><a href="{{ url('merge_federal_mda') }}">Merge Federal MDAs</a></li>
        </ul>
    </li>

    {{-- 2. Users --}}
    <li class="dropdown">
        <a href="#" class="dropdown-toggle disabled" data-toggle="dropdown" role="button"
           aria-haspopup="true" aria-expanded="false" disabled>
           Users <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="{{ url('fcc_user_list') }}">FCC Users</a></li>
            <li><a href="{{ url('mda_user_list') }}">MDA Users</a></li>
        </ul>
    </li>

    {{-- 3. Security --}}
    <li class="dropdown">
        <a href="#" class="dropdown-toggle disabled" data-toggle="dropdown" role="button"
           aria-haspopup="true" aria-expanded="false" disabled>
           Security <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li class="dropdown-header">Roles</li>
            <li><a href="{{ url('system_role_list') }}">System Roles</a></li>
        </ul>
    </li>
{{-- @endif --}}


{{-- 4. Nominal Roll (for MDA Uploaders) --}}
{{-- @if ($privilegeChecker?->canUploadFedMdaNominalRoll) --}}
    <li class="dropdown">
        <a href="#" class="dropdown-toggle disabled" data-toggle="dropdown" role="button"
           aria-haspopup="true" aria-expanded="false" disabled>
           Nominal Roll <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="{{ url('federal_mda_admin_nominal_roll_main_submission_list') }}">
                Main Submissions
            </a></li>
            <li><a href="{{ url('federal_mda_admin_nominal_roll_quarterly_return_list') }}">
                Quarterly Returns
            </a></li>
        </ul>
    </li>
{{-- @endif --}}


{{-- 5. Reports / Downloads / Notifications / More --}}
{{-- @if ($privilegeChecker?->appUser) --}}
    <li>
        <a href="{{ url('secure_area/notification/notification_inbox') }}">
            Notifications @yield('top_notification_badge')
        </a>
    </li>
{{-- @endif --}}

{{-- @if ($privilegeChecker?->isSuperAdmin || $privilegeChecker?->canUploadFedMdaNominalRoll || $privilegeChecker?->canUploadStateMdaNominalRoll)
 --}}    <li>
        <a href="{{ url('download_manager') }}" class="disabled" disabled>Downloads</a>
    </li>
{{-- @endif --}}

{{-- @if ($privilegeChecker?->canViewReports) --}}
    <li class="dropdown">
        <a href="#" class="dropdown-toggle disabled" data-toggle="dropdown" role="button"
           aria-haspopup="true" aria-expanded="false" disabled>
           Reports <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
           {{--  @if ($privilegeChecker?->canViewFederalReports) --}}
                <li class="dropdown-header">FEDERAL LEVEL</li>
                <li>
                    <a href="{{ url('federal_level_criteria_search_report') }}">
                        Federal Nominal Roll
                    </a>
                </li>
                <li>
                    <a href="{{ url('fed_level_career_dist_single_establishment') }}">
                        Single Establishment Career Post Dist.
                    </a>
                </li>
                <li>
                    <a href="{{ url('fed_level_career_dist_min_consolidated') }}">
                        Federal Consolidated Career Post Dist.
                    </a>
                </li>
                <li>
                    <a href="{{ url('fed_level_poloh_dist_consolidated') }}">
                        Federal Political Post
                    </a>
                </li>
                <li >
                    <a href="{{ url('fed_level_poloh_by_position_and_year_dist') }}">
                        Distribution Of Political Office By State Of Origin
                    </a>
                </li>
                <li >
                    <a href="{{ url('fed_level_comparative_data') }}">
                        Comparative Manpower Statistics
                    </a>
                </li>
                <li >
                    <a href="{{ url('fed_level_ceo_list') }}">
                        List Of Federal Chief Executives
                    </a>
                </li>
                <li >
                    <a href="{{ url('federal_level_character_balancing_index') }}">
                        Federal Character Balancing Index
                    </a>
                </li>
                <li>
                    <a href="{{ url('mda_cbi_report_request_list') }}">
                        Federal Character Balancing Index (Request)
                    </a>
                </li>
           {{--  @endif --}}
        </ul>
    </li>
{{-- @endif --}}


{{-- 6. More (if MIS Head or MDA uploader) --}}
{{-- @if ($privilegeChecker?->misHead) --}}
    <li class="dropdown">
        <a href="#" class="dropdown-toggle disabled" data-toggle="dropdown" role="button"
           aria-haspopup="true" aria-expanded="false" disabled>
           More <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="{{ url('static_cms_list') }}">CMS</a></li>
            <li><a href="{{ url('mis_head_cbi_report_request_pending_approval_list') }}">CBI Report Requests</a></li>
            <li><a href="{{ url('download_manager') }}">Downloads</a></li>
        </ul>
    </li>
{{-- @elseif ($privilegeChecker?->canUploadFedMdaNominalRoll) --}}
    <li class="dropdown">
        <a href="#" class="dropdown-toggle disabled" data-toggle="dropdown" role="button"
           aria-haspopup="true" aria-expanded="false" disabled>
           More <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="{{ url('mda_submission_permission_request_list') }}">
                Nominal Roll Submission Permission Requests
            </a></li>
            <li role="separator" class="divider"></li>
            <li class="dropdown-header">Help</li>
            <li><a href="http://federalcharacter.gov.ng/fcc-portal-help/" target="_blank">Portal Help</a></li>
            <li role="separator" class="divider"></li>
            <li class="dropdown-header">COMING SOON</li>
            <li><a href="#" disabled>Certificate Of Compliance</a></li>
            <li><a href="#" disabled>Regularization of Recruitment</a></li>
            <li><a href="#" disabled>Waivers</a></li>
            <li><a href="#" disabled>Manage Long List</a></li>
            <li><a href="#" disabled>Manage Short List</a></li>
        </ul>
    </li>
{{-- @endif --}}

                        </ul>
                    </div>
                </div>
            </nav>

            <div class="container-fluid page-toolbar">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pull-left">
                            <h4>@yield('page_toolbar_left')</h4>
                        </div>
                        <div class="pull-right page-toolbar-right">
                            @yield('page_toolbar_right')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @show

    <div class="content container-fluid1" style="overflow-x: hidden;margin-left: 10px;">
        @php($alert = session('alert'))
@if($alert && !empty($alert['messages']))
    <div class="alert alert-{{ $alert['type'] }}">
        <ul>
            @foreach($alert['messages'] as $msg)
                <li>{{ $msg }}</li>
            @endforeach
        </ul>
    </div>
@endif

        @yield('content')
    </div>
    
<!-- Footer -->
<footer class="bg-light text-center text-lg-start mt-5 border-top">
  <div class="container py-3">
    <div class="row">
      <div class="col-md-6 text-md-start text-center">
        <span class="text-muted">© 2025 FCC Portal. All rights reserved.</span>
      </div>
      <div class="col-md-6 text-md-end text-center">
        <span class="text-muted">Version: v1.0.5</span>
      </div>
    </div>
  </div>
</footer>
</div>
<!-- End Footer -->

    <div id="loading-overlay">
        <div class="spinner-wrapper">
            <div class="loader">
                <svg class="circular" viewBox="25 25 50 50">
                    <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10"></circle>
                </svg>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('select2-4.0.3/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        $(document).ready(function () {
            $(".select-2").select2({});
        });
    </script>

    @yield('javascripts')
    @stack('javascripts')
</body>
</html>
