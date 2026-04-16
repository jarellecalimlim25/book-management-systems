@extends('layouts.app')

@section('title', 'Books')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1>Books</h1>
    <a href="{{ route('books.create') }}" class="btn btn-success">+ Add New Book</a>
</div>

@if ($books->isEmpty())
    <div class="card text-center">
        <p>No books found. <a href="{{ route('books.create') }}">Add one now</a></p>
    </div>
@else
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Publication Year</th>
                <th>ISBN</th>
                <th>Pages</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($books as $book)
                <tr>
                    <td><strong>{{ $book->title }}</strong></td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->publication_year }}</td>
                    <td>{{ $book->isbn }}</td>
                    <td>{{ $book->pages }}</td>
                    <td>
                        <a href="{{ route('books.show', $book->id) }}" class="btn btn-primary btn-small">View</a>
                        <a href="{{ route('books.edit', $book->id) }}" class="btn btn-primary btn-small">Edit</a>
                        <form method="POST" action="{{ route('books.destroy', $book->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure?')">
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
        {{ $books->links() }}
    </div>
@endif
@endsection