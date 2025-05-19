<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * Display a listing of books with author info and borrowed status.
     */
    public function index(Request $request): View
    {
        $query = Book::with('author');

        if ($request->filled('author_id')) {
            $query->where('author_id', $request['author_id']);
        }

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request['title'] . '%');
        }

        if ($request->filled('is_borrowed')) {
            $isBorrowed = $request['is_borrowed'] === '1';
            $query->where('is_borrowed', $isBorrowed);
        }

        $books = $query->paginate(15)->withQueryString();

        $authors = Author::orderBy('surname')->get();

        return view('books.index', compact('books', 'authors'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create(): View
    {
        $authors = Author::orderBy('surname')->get();

        return view('books.create', compact('authors'));
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'author_id' => ['required', 'exists:authors,id'],
            'title' => ['required', 'string', 'max:255'],
            'is_borrowed' => ['nullable', 'boolean'],
        ]);

        $validated['is_borrowed'] = $validated['is_borrowed'] ?? false;

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Book created successfully.');
    }

    /**
     * Display the specified book.
     */
    public function show(Book $book): View
    {
        $book->load('author');

        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book): View
    {
        $authors = Author::orderBy('surname')->get();

        return view('books.edit', compact('book', 'authors'));
    }

    /**
     * Update the specified book in storage.
     */
    public function update(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'author_id' => ['required', 'exists:authors,id'],
            'title' => ['required', 'string', 'max:255'],
            'is_borrowed' => ['nullable', 'boolean'],
        ]);

        $validated['is_borrowed'] = $validated['is_borrowed'] ?? false;

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }

    /**
     * Toggle the borrowed status of a book from the list.
     */
    public function toggleBorrowed(Book $book): RedirectResponse
    {
        $book['is_borrowed'] = !$book['is_borrowed'];
        $book->save();

        return redirect()->route('books.index')->with('success', 'Book status updated.');
    }
}
