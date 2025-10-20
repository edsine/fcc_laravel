<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class CustomLoginController extends Controller
{
    // Show the login form (optional)
    public function showLoginForm()
    {
        return view('auth.login');  // Assuming you have a custom login view at resources/views/auth/login.blade.php
    }

    // Handle the login request
    public function login(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'user_login' => 'required|string',  // Make sure user_login is required and a string
            'password' => 'required|string',    // Password should also be required
        ]);

        // Attempt to authenticate the user
        $user = User::where('user_login', $request->user_login)->first();

        // Check if the user exists and if the password matches
        if ($user && Hash::check($request->password, $user->password)) {
            // If valid, log the user in
            Auth::login($user);

            // Redirect to the dashboard
            return redirect()->route('dashboard');
        }

        // If authentication fails, redirect back with an error message
        return back()->withErrors([
            'user_login' => 'The provided credentials do not match our records.',
        ]);
    }

    // Handle logout request
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
