@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="text-center">
    <h1>Login</h1>
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

<form method="POST" action="{{ route('login') }}" style="margin-left: auto; margin-right: auto;">
    @csrf

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
        <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
    </div>

    <div class="text-center">
        <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
    </div>
</form>
@endsection
