<?php

// app/Http/Controllers/SettingsController.php
namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        if(!empty(auth()->user()) && auth()->user()->id == 1){
        $settings = Setting::all(); // Retrieve all settings
        return view('settings.index', compact('settings'));
        } 
        else {
            return redirect()->back()->with('error', "Permission Denied.");
        }

    }

    public function create()
    {
        return view('settings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'provider_name' => 'required|string',
            'hostname' => 'required|string',
            'port' => 'required|integer',
            'ssl' => 'required|boolean',
        ]);

        Setting::create($validated);

        return redirect()->route('settings.index');
    }

    public function edit($id)
    {
        $setting = Setting::findOrFail($id);
        return view('settings.edit', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'provider_name' => 'required|string',
            'hostname' => 'required|string',
            'port' => 'required|integer',
            'ssl' => 'required|boolean',
        ]);

        $setting = Setting::findOrFail($id);
        $setting->update($validated);

        return redirect()->route('settings.index');
    }

    public function destroy($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->delete();

        return redirect()->route('settings.index');
    }
}

