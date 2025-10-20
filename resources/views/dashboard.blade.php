@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="panel panel-default">
    <div class="panel-body">
        Welcome to the Dashboard!
         {{ __("You're logged in!") }}
                    <h2>Welcome to your dashboard!</h2>
                    <a href="{{ route('logout') }}">Logout</a>
    </div>
</div>
@endsection
