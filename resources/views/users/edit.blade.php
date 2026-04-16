@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<h1>Edit User: {{ $user->name }}</h1>

@if ($errors->any())
    <div class="alert alert-error">
        <ul style="margin: 0; padding-left: 1.5rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('users.update', $user) }}">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        @error('name')
            <span style="color: #e74c3c; font-size: 0.875rem;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        @error('email')
            <span style="color: #e74c3c; font-size: 0.875rem;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="role">Role</label>
        <select id="role" name="role" required>
            <option value="">Select a role</option>
            <option value="user" @if (old('role', $user->role) === 'user') selected @endif>User</option>
            <option value="admin" @if (old('role', $user->role) === 'admin') selected @endif>Admin</option>
        </select>
        @error('role')
            <span style="color: #e74c3c; font-size: 0.875rem;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-success">Update User</button>
        <a href="{{ route('users.index') }}" class="btn btn-primary" style="text-align: center;">Cancel</a>
    </div>
</form>
@endsection
