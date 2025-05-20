@extends('layouts.app')

@section('title', 'Book Details')

@section('content')
    <div class="container">
        <h1>Book Details</h1>

        <div class="card mb-3">
            <div class="card-body">
                <div class="">
                    <strong>Title:</strong> {{ $book->title }}
                </div>

                <div class="">
                    <strong>Author:</strong> {{ $book->author->surname }}, {{ $book->author->name }}
                </div>

                <div class="">
                    <strong>Status:</strong>
                    @if($book->is_borrowed)
                        <span class="badge bg-danger">Borrowed</span>
                    @else
                        <span class="badge bg-success">Available</span>
                    @endif
                </div>
            </div>
        </div>

        <a href="{{ route('books.index') }}" class="btn btn-secondary">Back to List</a>
        <a href="{{ route('books.edit', $book) }}" class="btn btn-warning">Edit</a>
    </div>
@endsection
