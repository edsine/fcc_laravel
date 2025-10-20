@extends('layouts.app')
@section('page_toolbar_left')
Edit Your Profile
@endsection
@section('content')
<div class="container" style="margin-bottom: 30px;">
    <h2 class="mb-4"></h2>

    <!-- INFO NOTICE FOR 2FA EMAIL USERS -->
    <div class="alert alert-info d-flex align-items-start p-3 rounded shadow-sm">
        <div class="me-3">
            <i class="bi bi-shield-lock-fill text-primary" style="font-size: 2rem;"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-1">Important Notice for Gmail / Yahoo Mail Users with 2FA</h5>
            <p class="mb-2">
                Your webmail provider (like <strong>Gmail</strong> or <strong>Yahoo Mail</strong>) must have 
                <strong>two-factor authentication (2FA)</strong> enabled, your normal email password will
                <span class="text-danger fw-semibold">not work</span> for connecting to your inbox here.
            </p>
            <ul class="mb-0">
                <li>
  <strong>Gmail:</strong> 
  Go to <a href="https://mail.google.com/mail/u/0/#settings/fwdandpop" target="_blank">Gmail Settings</a> on the web, 
  find the <strong>“Forwarding and POP/IMAP”</strong> section, 
  and select <strong>“Enable IMAP/POP.”</strong> <br>
  Then go to your 
  <a href="https://myaccount.google.com/security" target="_blank">
    Google Account Security
  </a> → 
  <strong>App passwords</strong> → 
  generate one for “Mail” or “Other (Custom name)” and use that as your 
  <code>webmail_password</code>.
  <strong>Note: if you can not see app passwords click this link after you have enabled 2fa: https://myaccount.google.com/apppasswords and make sure all spaces are remove so that the password can be one word  only</strong>
</li>

                <li>
                    <strong>Yahoo Mail:</strong> Go to 
                    <a href="https://login.yahoo.com/account/security" target="_blank">Account Security</a> → 
                    <strong>Generate app password</strong> → use it as your <code>webmail_password</code>.
                </li>
                <li>
                    The same applies to <strong>any other provider</strong> with 2FA enabled.
                    You must create and use an <strong>app password</strong>.
                </li>
            </ul>
        </div>
    </div>
    <!-- END INFO NOTICE -->

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" name="first_name" 
                   value="{{ old('first_name', $user->first_name) }}">
        </div>

        <div class="form-group mb-3">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" name="last_name" 
                   value="{{ old('last_name', $user->last_name) }}">
        </div>

        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input type="email" class="form-control" readonly 
                   name="email" value="{{ old('email', $user->email) }}">
        </div>

        <div class="form-group mb-3">
            <label for="webmail_email">Webmail Email (Optional)</label>
            <input type="email" class="form-control" name="webmail_email" 
                   value="{{ old('webmail_email', $user->webmail_email) }}">
        </div>

        <div class="form-group mb-3">
            <label for="webmail_password">Webmail Password (Optional)</label>
            <input type="password" class="form-control" name="webmail_password" 
                   value="{{ old('webmail_password', $user->webmail_password) }}">
        </div>

        <div class="form-group mb-3">
            <label for="profile_picture">Profile Picture</label>
            <input type="file" class="form-control" name="profile_picture_file_name">
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> Update Profile
        </button>
    </form>
</div>
@endsection
