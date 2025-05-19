@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Authors</h1>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Filter form --}}
        <form method="GET" action="{{ route('authors.index') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="Filter by name">
            </div>
            <div class="col-md-4">
                <input type="text" name="surname" value="{{ request('surname') }}" class="form-control" placeholder="Filter by surname">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('authors.index') }}" class="btn btn-secondary">Reset</a>
                <a href="{{ route('authors.create') }}" class="btn btn-success float-end">Add New Author</a>
            </div>
        </form>

        @if($authors->isEmpty())
            <p>No authors found.</p>
        @else
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Books Count</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($authors as $author)
                    <tr>
                        <td>{{ $author->name }}</td>
                        <td>{{ $author->surname }}</td>
                        <td>{{ $author->books_count }}</td>
                        <td>
                            <a href="{{ route('authors.show', $author) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('authors.edit', $author) }}" class="btn btn-warning btn-sm">Edit</a>

                            <form action="{{ route('authors.destroy', $author) }}" method="POST" style="display:inline-block;"
                                  onsubmit="return confirm('Are you sure you want to delete this author?');">
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
    </div>
@endsection
