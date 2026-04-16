@extends('layouts.app')

@section('title', 'Posts')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1>Posts</h1>
    <a href="{{ route('posts.create') }}" class="btn btn-success">+ Create Post</a>
</div>

@if ($posts->isEmpty())
    <div class="card text-center">
        <p>No posts found. <a href="{{ route('posts.create') }}">Create one now</a></p>
    </div>
@else
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
                <tr>
                    <td><strong>{{ $post->title }}</strong></td>
                    <td>{{ $post->user->name }}</td>
                    <td>{{ $post->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-primary btn-small">View</a>
                        @if (auth()->user()->id === $post->user_id || auth()->user()->role === 'admin')
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary btn-small">Edit</a>
                            <form method="POST" action="{{ route('posts.destroy', $post) }}" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-small">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">
        {{ $posts->links() }}
    </div>
@endif
@endsection
