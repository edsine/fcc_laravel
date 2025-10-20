@extends('layouts.login')

@section('title', 'Login - Federal Character Commission')

@section('main')
    <h2 class="mb-4 text-center">Welcome back!</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('custom.login') }}" class="text-center">
        @csrf

        <div class="form-group">
            <label class="text-muted" for="user_login">Username</label>
            <input type="text" placeholder="Enter your Username" class="form-control form-control-line" name="user_login" value="{{ old('user_login') }}" required autocomplete="off">
        </div>

        <div class="form-group">
            <label class="text-muted" for="password">Password</label>
            <input type="password" placeholder="Enter Your Password" class="form-control form-control-line" name="password" required autocomplete="off">
        </div>

        <div class="form-group mr-b-20" style="margin-top: 40px;">
            <button class="btn btn-block btn-rounded btn-md btn-color-scheme text-uppercase fw-600 ripple" type="submit">Sign In</button>
        </div>

        <div class="form-group no-gutters mb-5 text-center" style="margin-top: 40px;">
            <a href="{{ route('password.request') }}" class="text-muted fw-700 text-uppercase heading-font-family fs-12">Forgot Password?</a>
        </div>
    </form>
@endsection
