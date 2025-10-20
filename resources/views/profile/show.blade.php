<!-- resources/views/profile/show.blade.php -->

@extends('layouts.app')
@section('page_toolbar_left')
Your Profile
@endsection
@section('content')
    <div class="container">
        <h2></h2>
        <div class="row">
            <div class="col-md-4">
                <div class="profile-picture">
                    <!-- Display the profile picture or default if not set -->
                    @if (!empty($user->profile_pictrue_file_name))
                    <img src="{{ $user->profile_pictrue_file_name ? asset('storage/' . $user->profile_pictrue_file_name) : asset('images/default-avatar.png') }}" alt="Profile Picture" class="img-thumbnail">
                    @else
                     <img class="img-thumbnail" src="{{ asset('images/user-128px.png') }}" />
                    @endif
                    
                </div>
            </div>
            <div class="col-md-8">
                <ul class="list-group">
                    <li class="list-group-item">
                        <strong>Full Name:</strong> {{ $user->first_name }} {{ $user->middle_name ?? '' }} {{ $user->last_name }}
                    </li>
                    <li class="list-group-item">
                        <strong>Email:</strong> {{ $user->email }}
                    </li>
                    <li class="list-group-item">
                        <strong>User Login:</strong> {{ $user->user_login }}
                    </li>
                    <li class="list-group-item">
                        <strong>Phone:</strong> {{ $user->primary_phone }} (Primary) / {{ $user->secondary_phone }} (Secondary)
                    </li>
                    <li class="list-group-item">
                        <strong>Organization:</strong> {{ $user->getOrganizationName() }}
                    </li>
                    <li class="list-group-item">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
