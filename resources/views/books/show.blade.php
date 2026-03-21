<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Book Details</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="200">Title</th>
                                <td>{{ $book->title }}</td>
                            </tr>
                            <tr>
                                <th>Author</th>
                                <td>{{ $book->author }}</td>
                            </tr>
                            <tr>
                                <th>Publication Year</th>
                                <td>{{ $book->publication_year }}</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $book->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $book->updated_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        </table>

                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('books.index') }}" class="btn btn-secondary">Back to List</a>
                            <div>
                                <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this student?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
