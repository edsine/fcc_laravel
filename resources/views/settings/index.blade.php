<!-- resources/views/settings/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Mail Providers Settings</h2>
        <a href="{{ route('settings.create') }}" class="btn btn-primary mb-3">Add New Provider</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Provider</th>
                    <th>Hostname</th>
                    <th>Port</th>
                    <th>SSL</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($settings as $setting)
                    <tr>
                        <td>{{ $setting->provider_name }}</td>
                        <td>{{ $setting->hostname }}</td>
                        <td>{{ $setting->port }}</td>
                        <td>{{ $setting->ssl ? 'Yes' : 'No' }}</td>
                        <td>
                            <a href="{{ route('settings.edit', $setting->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('settings.destroy', $setting->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
