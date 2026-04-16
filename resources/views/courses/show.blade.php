@extends('layouts.app')

@section('title', 'Course: ' . $course->name)

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1>{{ $course->name }}</h1>
    <div style="display: flex; gap: 1rem;">
        <a href="{{ route('courses.edit', $course) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('courses.index') }}" class="btn btn-primary">Back to Courses</a>
    </div>
</div>

<div class="card">
    <p><strong>Created At:</strong> {{ $course->created_at->format('M d, Y H:i') }}</p>
    <p><strong>Total Enrolled:</strong> {{ $users->total() }}</p>
</div>

<h3 style="margin-top: 2rem;">Enrolled Users</h3>

@if ($users->isEmpty())
    <div class="card text-center">
        <p>No users enrolled in this course yet.</p>
    </div>
@else
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Enrolled At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td><a href="{{ route('users.show', $user) }}" style="color: #3498db;">{{ $user->name }}</a></td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>{{ $user->pivot->created_at ? $user->pivot->created_at->format('M d, Y') : 'N/A' }}</td>
                    <td>
                        <form method="POST" action="{{ route('courses.removeUser', [$course, $user]) }}" style="display: inline;" onsubmit="return confirm('Remove this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-small">Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">
        {{ $users->links() }}
    </div>
@endif

<h3 style="margin-top: 2rem;">Enroll New User</h3>
<form method="POST" action="{{ route('courses.enroll', $course) }}" style="max-width: 400px;">
    @csrf

    <div class="form-group">
        <label for="user_id">Select User</label>
        <select id="user_id" name="user_id" required>
            <option value="">Choose a user...</option>
            @php
                $enrolledIds = $course->users->pluck('id')->toArray();
                $availableUsers = \App\Models\User::whereNotIn('id', $enrolledIds)->get();
            @endphp
            @foreach ($availableUsers as $user)
                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-success">Enroll User</button>
</form>
@endsection
