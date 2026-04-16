@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1>Users</h1>
    <a href="{{ route('users.create') }}" class="btn btn-success">+ Create User</a>
</div>

@if ($users->isEmpty())
    <div class="card text-center">
        <p>No users found. <a href="{{ route('users.create') }}">Create one now</a></p>
    </div>
@else
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span style="padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.875rem; font-weight: bold; @if ($user->role === 'admin') background-color: #e74c3c; color: white; @else background-color: #3498db; color: white; @endif">
                            {{ strtoupper($user->role) }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('users.show', $user) }}" class="btn btn-primary btn-small">View</a>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-small">Edit</a>
                        <form method="POST" action="{{ route('users.destroy', $user) }}" style="display: inline;" onsubmit="return confirm('Are you sure?')">
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
        {{ $users->links() }}
    </div>
@endif
@endsection
