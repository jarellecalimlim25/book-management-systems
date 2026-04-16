@extends('layouts.app')

@section('title', 'Create Course')

@section('content')
<h1>Create New Course</h1>

@if ($errors->any())
    <div class="alert alert-error">
        <ul style="margin: 0; padding-left: 1.5rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('courses.store') }}">
    @csrf

    <div class="form-group">
        <label for="name">Course Name</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        @error('name')
            <span style="color: #e74c3c; font-size: 0.875rem;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-success">Create Course</button>
        <a href="{{ route('courses.index') }}" class="btn btn-primary" style="text-align: center;">Cancel</a>
    </div>
</form>
@endsection
