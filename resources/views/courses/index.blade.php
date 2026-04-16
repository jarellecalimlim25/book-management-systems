@extends('layouts.app')

@section('title', 'Courses')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1>Courses</h1>
    <a href="{{ route('courses.create') }}" class="btn btn-success">+ Create Course</a>
</div>

@if ($courses->isEmpty())
    <div class="card text-center">
        <p>No courses found. <a href="{{ route('courses.create') }}">Create one now</a></p>
    </div>
@else
    <table>
        <thead>
            <tr>
                <th>Course Name</th>
                <th>Enrolled Students</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($courses as $course)
                <tr>
                    <td><strong>{{ $course->name }}</strong></td>
                    <td>{{ $course->users_count ?? $course->users->count() }}</td>
                    <td>{{ $course->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-primary btn-small">View</a>
                        <a href="{{ route('courses.edit', $course) }}" class="btn btn-primary btn-small">Edit</a>
                        <form method="POST" action="{{ route('courses.destroy', $course) }}" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-small">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">
        {{ $courses->links() }}
    </div>
@endif
@endsection
