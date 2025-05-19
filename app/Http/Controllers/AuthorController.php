<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\AuthorService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Class AuthorController
 *
 * Handles HTTP requests related to author management.
 * Implements RESTful CRUD operations for authors with proper validation and response handling.
 */
class AuthorController extends Controller
{
    /**
     * @var AuthorService Service layer handling author-related business logic
     */
    protected AuthorService $authorService;

    /**
     * Initialize a controller with its required dependencies.
     *
     * @param AuthorService $authorService The service handling author operations
     */
    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    /**
     * Display a listing of authors.
     * Retrieves authors with book counts or filtered based on provided criteria.
     *
     * @param Request $request The incoming HTTP request
     * @return View The authors listing view with authors and filters data
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['name', 'surname']);

        if (empty(array_filter($filters))) {
            $authors = $this->authorService->getAllAuthorsWithBookCount();
        } else {
            $authors = $this->authorService->getFilteredAuthors($filters);
        }

        return view('authors.index', compact('authors', 'filters'));
    }

    /**
     * Show the form for creating a new author.
     *
     * @return View Returns the creation form view
     */
    public function create(): View
    {
        return view('authors.create');
    }

    /**
     * Store a newly created author in the database.
     * Validates input data before creation.
     *
     * @param Request $request The incoming HTTP request
     * @return RedirectResponse Redirects to index with a success message
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
        ]);

        $this->authorService->createAuthor($validated);

        return redirect()->route('authors.index')
            ->with('success', 'Author created successfully.');
    }

    /**
     * Display the specified author with additional details.
     * Returns 404 if the author is not found.
     *
     * @param int $id The author's ID
     * @return View Returns the show view with author data
     */
    public function show(int $id): View
    {
        $author = $this->authorService->findAuthor($id);

        if (!$author) {
            abort(404);
        }

        $author->loadCount('books');

        return view('authors.show', compact('author'));
    }

    /**
     * Show the form for editing the specified author.
     * Returns 404 if the author is not found.
     *
     * @param int $id The author's ID
     * @return View Returns the edit form view with author data
     */
    public function edit(int $id): View
    {
        $author = $this->authorService->findAuthor($id);

        if (!$author) {
            abort(404);
        }

        return view('authors.edit', compact('author'));
    }

    /**
     * Update the specified author in the database.
     * Validates input data before update.
     *
     * @param Request $request The incoming HTTP request
     * @param int $id The author's ID
     * @return RedirectResponse Redirects to index with a success message
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
        ]);

        $this->authorService->updateAuthor($id, $validated);

        return redirect()->route('authors.index')
            ->with('success', 'Author updated successfully.');
    }

    /**
     * Remove the specified author from the database.
     *
     * @param int $id The author's ID
     * @return RedirectResponse Redirects to index with a success message
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->authorService->deleteAuthor($id);

        return redirect()->route('authors.index')
            ->with('success', 'Author deleted successfully.');
    }
}
