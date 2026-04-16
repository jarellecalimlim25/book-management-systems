@extends('layouts.app')

@section('title', $book->title)

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1>{{ $book->title }}</h1>
    <div style="display: flex; gap: 1rem;">
        <a href="{{ route('books.edit', $book->id) }}" class="btn btn-primary">Edit</a>
        <form method="POST" action="{{ route('books.destroy', $book->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
        <a href="{{ route('books.index') }}" class="btn btn-primary">Back to Books</a>
    </div>
</div>

<div class="card">
    <div style="margin-bottom: 1rem;">
        <strong>Author:</strong> {{ $book->author }}
    </div>
    <div style="margin-bottom: 1rem;">
        <strong>Publication Year:</strong> {{ $book->publication_year }}
    </div>
    <div style="margin-bottom: 1rem;">
        <strong>ISBN:</strong> {{ $book->isbn }}
    </div>
    <div style="margin-bottom: 1rem;">
        <strong>Pages:</strong> {{ $book->pages }}
    </div>
    <div style="margin-bottom: 1rem;">
        <strong>Created At:</strong> {{ $book->created_at->format('M d, Y H:i') }}
    </div>
    <div>
        <strong>Updated At:</strong> {{ $book->updated_at->format('M d, Y H:i') }}
    </div>
</div>
@endsection
