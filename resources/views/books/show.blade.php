@extends('layouts.app')

@section('title', 'Book Details')

@section('content')
    <h1>Book Details</h1>

    <div class="mb-3">
        <strong>Title:</strong> {{ $book->title }}
    </div>

    <div class="mb-3">
        <strong>Author:</strong> {{ $book->author->surname }}, {{ $book->author->name }}
    </div>

    <div class="mb-3">
        <strong>Status:</strong>
        @if($book->is_borrowed)
            <span class="badge bg-danger">Borrowed</span>
        @else
            <span class="badge bg-success">Available</span>
        @endif
    </div>

    <a href="{{ route('books.index') }}" class="btn btn-secondary">Back to List</a>
    <a href="{{ route('books.edit', $book) }}" class="btn btn-warning">Edit</a>
@endsection
