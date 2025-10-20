@extends('layouts.login') <!-- or your base layout -->

@section('main')
    <div class="container">
        <h2 class="mb-4 text-center">Reset Your Password</h2>

        <!-- Display alert message if there is any session flash message -->
        @if (session('status'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="text-center">
            @csrf

            <div class="form-group">
                <label class="text-muted" for="email">Email</label>
                <input type="email" placeholder="Enter Your Email Address" class="form-control form-control-line" 
                       name="email" value="{{ old('email') }}" autocomplete="off">
            </div>

            <!-- Link to login page -->
            <div class="form-group no-gutters mb-5 text-center">
                <a href="{{ route('login') }}" class="text-muted fw-700 text-uppercase heading-font-family fs-12">Login</a>
            </div>

            <!-- Submit button -->
            <div class="form-group mr-b-20">
                <button class="btn btn-block btn-rounded btn-md btn-color-scheme text-uppercase fw-600 ripple" type="submit">
                    Submit
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <!-- Additional JS if needed -->
@endsection
