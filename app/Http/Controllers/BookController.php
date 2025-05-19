<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\BookService;
use App\Services\AuthorService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Class BookController
 *
 * Handles HTTP requests related to book management.
 * Implements RESTful CRUD operations for books and manages book borrowing status.
 * Integrates with both BookService and AuthorService for complete book management.
 */
class BookController extends Controller
{
    /**
     * @var BookService Service layer handling book-related operations
     */
    protected BookService $bookService;

    /**
     * @var AuthorService Service layer handling author-related operations
     */
    protected AuthorService $authorService;

    /**
     * Initialize a controller with its required dependencies.
     *
     * @param BookService $bookService Service for book operations
     * @param AuthorService $authorService Service for author operations
     */
    public function __construct(BookService $bookService, AuthorService $authorService)
    {
        $this->bookService = $bookService;
        $this->authorService = $authorService;
    }

    /**
     * Display a listing of all books.
     * Shows books with their associated author information.
     *
     * @return View Returns the index view with books data
     */
    public function index(): View
    {
        $books = $this->bookService->getAllBooks();

        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new book.
     * Includes a list of all available authors for selection.
     *
     * @return View Returns the creation form view with an author list
     */
    public function create(): View
    {
        $authors = $this->authorService->getAllAuthors();

        return view('books.create', compact('authors'));
    }

    /**
     * Store a newly created book in the database.
     * Validates input data including author existence before creation.
     *
     * @param Request $request The incoming HTTP request
     * @return RedirectResponse Redirects to index with a success message
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'is_borrowed' => 'boolean',
        ]);

        $this->bookService->createBook($validated);

        return redirect()->route('books.index')
            ->with('success', 'Book created successfully.');
    }

    /**
     * Display the specified book.
     * Returns 404 if a book is not found.
     *
     * @param int $id The book's ID
     * @return View Returns the show view with book data
     */
    public function show(int $id): View
    {
        $book = $this->bookService->findBook($id);

        if (!$book) {
            abort(404);
        }

        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified book.
     * Includes a list of all authors for selection.
     * Returns 404 if a book is not found.
     *
     * @param int $id The book's ID
     * @return View Returns the edit form view with book data and authors list
     */
    public function edit(int $id): View
    {
        $book = $this->bookService->findBook($id);

        if (!$book) {
            abort(404);
        }

        $authors = $this->authorService->getAllAuthors();

        return view('books.edit', compact('book', 'authors'));
    }

    /**
     * Update the specified book in the database.
     * Validates input data including author existence before update.
     *
     * @param Request $request The incoming HTTP request
     * @param int $id The book's ID
     * @return RedirectResponse Redirects to index with a success message
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'is_borrowed' => 'boolean',
        ]);

        $this->bookService->updateBook($id, $validated);

        return redirect()->route('books.index')
            ->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified book from the database.
     *
     * @param int $id The book's ID
     * @return RedirectResponse Redirects to index with a success message
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->bookService->deleteBook($id);

        return redirect()->route('books.index')
            ->with('success', 'Book deleted successfully.');
    }

    /**
     * Toggle the borrowed status of a specific book.
     * Switches between borrowed and available states.
     *
     * @param int $id The book's ID
     * @return RedirectResponse Redirects to index with a success message
     */
    public function toggleBorrowed(int $id): RedirectResponse
    {
        $this->bookService->toggleBorrowed($id);

        return redirect()->route('books.index')->with('success', 'Book borrow status toggled.');
    }
}
