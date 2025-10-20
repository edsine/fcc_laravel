@extends('layouts.login') <!-- Assuming you're using the main layout, replace with your layout -->

@section('main')
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Forgot your password? No worries. Just let us know your email address and we’ll send you a link to reset your password.') }}
    </div>

    <!-- Success Message after Reset Link is Sent -->
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ session('status') }}
        </div>
    @endif

    <!-- Reset Password Form -->
    <form method="POST" action="{{ route('password.email') }}" class="text-center">
        @csrf

        <!-- Email Input Field -->
        <div class="form-group">
            <label class="text-muted" for="email">{{ __('Email Address') }}</label>
            <input type="email" name="email" id="email" placeholder="Enter your email address"
                   class="form-control form-control-line @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>

            <!-- Validation Error -->
            @error('email')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="form-group mt-4">
            <button type="submit" class="btn btn-block btn-rounded btn-md btn-color-scheme text-uppercase fw-600 ripple">
                {{ __('Send Password Reset Link') }}
            </button>
        </div>

        <!-- Back to Login Link -->
        <div class="form-group no-gutters mb-5 text-center mt-4">
            <a href="{{ route('login') }}" class="text-muted fw-700 text-uppercase heading-font-family fs-12">
                {{ __('Back to Login') }}
            </a>
        </div>
    </form>
@endsection
