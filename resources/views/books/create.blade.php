@extends('layouts.app')

@section('title', 'Add New Book')

@section('content')
    <h1>Add New Book</h1>

    <form method="POST" action="{{ route('books.store') }}">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Book Title</label>
            <input id="title" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" required>
            @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="author_id" class="form-label">Author</label>
            <select id="author_id" name="author_id" class="form-select @error('author_id') is-invalid @enderror" required>
                <option value="">Select author</option>
                @foreach($authors as $author)
                    <option value="{{ $author->id }}" @selected(old('author_id') == $author->id)>
                        {{ $author->surname }}, {{ $author->name }}
                    </option>
                @endforeach
            </select>
            @error('author_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" value="1" id="is_borrowed" name="is_borrowed" @checked(old('is_borrowed'))>
            <label class="form-check-label" for="is_borrowed">
                Borrowed
            </label>
        </div>

        <button type="submit" class="btn btn-primary">Create Book</button>
        <a href="{{ route('books.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
