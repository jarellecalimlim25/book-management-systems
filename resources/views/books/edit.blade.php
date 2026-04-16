@extends('layouts.app')

@section('title', 'Edit Book')

@section('content')
<h1>Edit Book</h1>

@if($errors->any())
    <div class="alert alert-error">
        <ul style="margin: 0; padding-left: 1.5rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('books.update', $book->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="{{ old('title', $book->title) }}" required>
        @error('title')
            <span style="color: #e74c3c; font-size: 0.875rem;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="author">Author</label>
        <input type="text" id="author" name="author" value="{{ old('author', $book->author) }}" required>
        @error('author')
            <span style="color: #e74c3c; font-size: 0.875rem;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="publication_year">Publication Year</label>
        <input type="number" id="publication_year" name="publication_year" value="{{ old('publication_year', $book->publication_year) }}" required>
        @error('publication_year')
            <span style="color: #e74c3c; font-size: 0.875rem;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="isbn">ISBN</label>
        <input type="text" id="isbn" name="isbn" value="{{ old('isbn', $book->isbn) }}" required>
        @error('isbn')
            <span style="color: #e74c3c; font-size: 0.875rem;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="pages">Pages</label>
        <input type="number" id="pages" name="pages" value="{{ old('pages', $book->pages) }}" required>
        @error('pages')
            <span style="color: #e74c3c; font-size: 0.875rem;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-success">Update Book</button>
        <a href="{{ route('books.index') }}" class="btn btn-primary" style="text-align: center;">Cancel</a>
    </div>
</form>
@endsection
