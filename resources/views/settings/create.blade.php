<!-- resources/views/settings/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Add New Mail Provider</h2>
        <form action="{{ route('settings.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="provider_name">Provider Name</label>
                <input type="text" name="provider_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="hostname">Hostname</label>
                <input type="text" name="hostname" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="port">Port</label>
                <input type="number" name="port" class="form-control" value="993" required>
            </div>
            <div class="form-group">
                <label for="ssl">SSL</label>
                <select name="ssl" class="form-control" required>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
@endsection
