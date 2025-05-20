@extends('layouts.app')

@section('title', 'Author Details')

@section('content')
    <div class="container">
        <h1>Author Details</h1>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $author->name }} {{ $author->surname }}</h5>
                <p class="card-text">Number of Books: {{ $author->books_count }}</p>
            </div>
        </div>

        <a href="{{ route('authors.index') }}" class="btn btn-secondary">Back to List</a>
        <a href="{{ route('authors.edit', $author) }}" class="btn btn-warning">Edit</a>
    </div>
@endsection
