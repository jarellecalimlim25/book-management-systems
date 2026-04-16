@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1>{{ $post->title }}</h1>
        <p style="color: #666; margin: 0;">By {{ $post->user->name }} on {{ $post->created_at->format('M d, Y') }}</p>
    </div>
    <div style="display: flex; gap: 1rem;">
        @if (auth()->user()->id === $post->user_id || auth()->user()->role === 'admin')
            <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary">Edit</a>
            <form method="POST" action="{{ route('posts.destroy', $post) }}" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        @endif
        <a href="{{ route('posts.index') }}" class="btn btn-primary">Back to Posts</a>
    </div>
</div>

<div class="card">
    <div style="line-height: 1.8; white-space: pre-wrap;">{{ $post->content }}</div>
</div>
@endsection
