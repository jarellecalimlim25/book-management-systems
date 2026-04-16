@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1>Welcome, {{ auth()->user()->name }}!</h1>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 2rem;">
    <div class="card">
        <h3>📚 Books</h3>
        <p>Manage your book collection.</p>
        <a href="{{ route('books.index') }}" class="btn btn-primary">View Books</a>
    </div>

    <div class="card">
        <h3>📝 Posts</h3>
        <p>Create and manage your posts.</p>
        <a href="{{ route('posts.index') }}" class="btn btn-primary">View Posts</a>
    </div>

    @if (auth()->user()->role === 'admin')
        <div class="card">
            <h3>👥 Users</h3>
            <p>Manage system users.</p>
            <a href="{{ route('users.index') }}" class="btn btn-primary">View Users</a>
        </div>

        <div class="card">
            <h3>📖 Courses</h3>
            <p>Manage courses and enrollments.</p>
            <a href="{{ route('courses.index') }}" class="btn btn-primary">View Courses</a>
        </div>
    @endif
</div>
@endsection
