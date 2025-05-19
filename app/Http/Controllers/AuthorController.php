<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a list of authors with book count.
     */
    public function index(Request $request): View
    {
        $query = Author::withCount('books');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request['name'] . '%');
        }

        if ($request->filled('surname')) {
            $query->where('surname', 'like', '%' . $request['surname'] . '%');
        }

        $authors = $query->orderBy('surname')->get();

        return view('authors.index', compact('authors'));
    }

    /**
     * Show the form to create a new author.
     */
    public function create(): View
    {
        return view('authors.create');
    }

    /**
     * Store a newly created author.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
        ]);

        Author::create($validated);

        return redirect()->route('authors.index')->with('success', 'Author created successfully.');
    }

    /**
     * Display the specified author with book count.
     */
    public function show(Author $author): View
    {
        $author->loadCount('books');

        return view('authors.show', compact('author'));
    }

    /**
     * Show form to edit an existing author.
     */
    public function edit(Author $author): View
    {
        return view('authors.edit', compact('author'));
    }

    /**
     * Update the specified author.
     */
    public function update(Request $request, Author $author): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
        ]);

        $author->update($validated);

        return redirect()->route('authors.index')->with('success', 'Author updated successfully.');
    }

    /**
     * Delete the specified author (only if no books are assigned).
     */
    public function destroy(Author $author): RedirectResponse
    {
        $author->loadCount('books');

        if ($author['books_count'] > 0) {
            return redirect()->route('authors.index')
                ->with('error', 'Author cannot be deleted because they have associated books.');
        }

        $author->delete();

        return redirect()->route('authors.index')->with('success', 'Author deleted successfully.');
    }
}
