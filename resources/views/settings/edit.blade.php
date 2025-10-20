<!-- resources/views/settings/edit.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Mail Provider Settings</h2>
        <form action="{{ route('settings.update', $setting->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="provider_name">Provider Name</label>
                <input type="text" name="provider_name" class="form-control" value="{{ $setting->provider_name }}" required>
            </div>
            <div class="form-group">
                <label for="hostname">Hostname</label>
                <input type="text" name="hostname" class="form-control" value="{{ $setting->hostname }}" required>
            </div>
            <div class="form-group">
                <label for="port">Port</label>
                <input type="number" name="port" class="form-control" value="{{ $setting->port }}" required>
            </div>
            <div class="form-group">
                <label for="ssl">SSL</label>
                <select name="ssl" class="form-control" required>
                    <option value="1" {{ $setting->ssl ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$setting->ssl ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <button type="submit" class="btn btn-warning">Update</button>
        </form>
    </div>
@endsection
