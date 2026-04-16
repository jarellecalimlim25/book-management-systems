@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
<h1>Create New Post</h1>

@if ($errors->any())
    <div class="alert alert-error">
        <ul style="margin: 0; padding-left: 1.5rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('posts.store') }}">
    @csrf

    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="{{ old('title') }}" required>
        @error('title')
            <span style="color: #e74c3c; font-size: 0.875rem;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="content">Content</label>
        <textarea id="content" name="content" required>{{ old('content') }}</textarea>
        @error('content')
            <span style="color: #e74c3c; font-size: 0.875rem;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-success">Create Post</button>
        <a href="{{ route('posts.index') }}" class="btn btn-primary" style="text-align: center;">Cancel</a>
    </div>
</form>
@endsection
