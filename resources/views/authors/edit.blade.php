@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Author</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('authors.update', $author) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">First Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $author->name) }}" class="form-control" required maxlength="255">
            </div>

            <div class="mb-3">
                <label for="surname" class="form-label">Last Name</label>
                <input type="text" name="surname" id="surname" value="{{ old('surname', $author->surname) }}" class="form-control" required maxlength="255">
            </div>

            <button type="submit" class="btn btn-primary">Update Author</button>
            <a href="{{ route('authors.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
