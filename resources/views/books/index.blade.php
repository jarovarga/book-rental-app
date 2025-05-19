@extends('layouts.app')

@section('title', 'Books List')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Books</h1>
        <a href="{{ route('books.create') }}" class="btn btn-primary">Add New Book</a>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Filter form --}}
    <form method="GET" action="{{ route('books.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="title" value="{{ request('title') }}" class="form-control" placeholder="Search by Title">
        </div>
        <div class="col-md-3">
            <select name="author_id" class="form-select">
                <option value="">All Authors</option>
                @foreach($authors as $author)
                    <option value="{{ $author->id }}" @selected(request('author_id') == $author->id)>
                        {{ $author->surname }}, {{ $author->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="is_borrowed" class="form-select">
                <option value="">All Statuses</option>
                <option value="1" @selected(request('is_borrowed') === '1')>Borrowed</option>
                <option value="0" @selected(request('is_borrowed') === '0')>Available</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('books.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    @if($books->isEmpty())
        <p>No books found.</p>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Borrowed</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($books as $book)
                <tr>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author->surname }}, {{ $book->author->name }}</td>
                    <td>
                        <form action="{{ route('books.toggleBorrowed', $book) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $book->is_borrowed ? 'btn-danger' : 'btn-success' }}">
                                {{ $book->is_borrowed ? 'Borrowed' : 'Available' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('books.show', $book) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('books.edit', $book) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('books.destroy', $book) }}" method="POST" style="display:inline-block;"
                              onsubmit="return confirm('Are you sure you want to delete this book?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endsection
