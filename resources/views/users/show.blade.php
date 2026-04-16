@extends('layouts.app')

@section('title', 'User: ' . $user->name)

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1>{{ $user->name }}</h1>
    <div style="display: flex; gap: 1rem;">
        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('users.index') }}" class="btn btn-primary">Back to Users</a>
    </div>
</div>

<div class="card">
    <div style="margin-bottom: 1.5rem;">
        <strong>Email:</strong> {{ $user->email }}
    </div>
    <div style="margin-bottom: 1.5rem;">
        <strong>Role:</strong> 
        <span style="padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.875rem; font-weight: bold; @if ($user->role === 'admin') background-color: #e74c3c; color: white; @else background-color: #3498db; color: white; @endif">
            {{ strtoupper($user->role) }}
        </span>
    </div>
    <div style="margin-bottom: 1.5rem;">
        <strong>Created At:</strong> {{ $user->created_at->format('M d, Y H:i') }}
    </div>
    <div style="margin-bottom: 1.5rem;">
        <strong>Updated At:</strong> {{ $user->updated_at->format('M d, Y H:i') }}
    </div>
</div>

@if ($user->posts->isNotEmpty())
    <h3 style="margin-top: 2rem;">Posts by {{ $user->name }} ({{ $user->posts->count() }})</h3>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($user->posts as $post)
                <tr>
                    <td><a href="{{ route('posts.show', $post) }}" style="color: #3498db;">{{ $post->title }}</a></td>
                    <td>{{ $post->created_at->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

@if ($user->courses->isNotEmpty())
    <h3 style="margin-top: 2rem;">Enrolled Courses ({{ $user->courses->count() }})</h3>
    <table>
        <thead>
            <tr>
                <th>Course Name</th>
                <th>Enrolled At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($user->courses as $course)
                <tr>
                    <td><a href="{{ route('courses.show', $course) }}" style="color: #3498db;">{{ $course->name }}</a></td>
                    <td>{{ $course->pivot->created_at ? $course->pivot->created_at->format('M d, Y') : 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection
