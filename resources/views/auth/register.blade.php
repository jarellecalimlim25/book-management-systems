@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="text-center">
    <h1>Register</h1>
</div>

@if ($errors->any())
    <div class="alert alert-error" style="max-width: 600px; margin: 0 auto 2rem;">
        <ul style="margin: 0; padding-left: 1.5rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('register') }}" style="margin-left: auto; margin-right: auto;">
    @csrf

    <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        @error('name')
            <span style="color: #e74c3c; font-size: 0.875rem;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        @error('email')
            <span style="color: #e74c3c; font-size: 0.875rem;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        @error('password')
            <span style="color: #e74c3c; font-size: 0.875rem;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success" style="width: 100%;">Register</button>
    </div>

    <div class="text-center">
        <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
    </div>
</form>
@endsection
