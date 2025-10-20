<?php

// app/Http/Controllers/MyProfileController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MyProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user(); // Get the logged-in user
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user(); // Get the logged-in user
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'webmail_email' => 'nullable|email', // Validate webmail_email if provided
            'webmail_password' => 'nullable|string', // Validate webmail_password if provided
            'profile_picture_file_name' => 'nullable|image', 
        ]);

        if ($request->hasFile('profile_picture_file_name')) {
            // Store the profile picture if provided 
            $validated['profile_picture_file_name'] = $request->file('profile_picture_file_name')->store('profile_pictures');
        }

        $validated['webmail_password'] = encrypt($request->webmail_password);
            
        $user->update($validated);

        return redirect()->route('profile.show');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

