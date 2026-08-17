@extends('layouts.app')
@section('title', 'Manage Books')
@section('content')
<div class="card" style="margin-bottom: 2rem;">
    <h2>Add New Book</h2>
    <form method="POST" action="{{ route('books.store') }}" style="margin-top: 1rem; max-width: 500px;">
        @csrf
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Author</label>
            <input type="text" name="author" class="form-control" required>
        </div>
        <div class="form-group">
            <label>ISBN</label>
            <input type="text" name="isbn" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Copies</label>
            <input type="number" name="copies" class="form-control" required min="0">
        </div>
        <button type="submit" class="btn">Add Book</button>
    </form>
</div>

<div class="card">
    <h1 class="page-title">Existing Books</h1>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Copies</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($books as $book)
                <tr>
                    <td>{{ $book->id }}</td>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->isbn }}</td>
                    <td>
                        <form action="{{ route('books.update', $book->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <input type="number" name="copies" value="{{ $book->copies }}" style="width: 70px; padding: 0.4rem; border-radius: 4px;" class="form-control" min="0">
                            <button type="submit" class="btn btn-sm">Save</button>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this book?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
