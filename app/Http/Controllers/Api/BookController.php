<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BookService;
use App\Services\AuthorService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
     * Display a listing of books.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['title', 'author_id', 'is_borrowed']);

        if (empty(array_filter($filters))) {
            $books = $this->bookService->getAllBooks();
        } else {
            $books = $this->bookService->getFilteredBooks($filters);
        }

        return response()->json([
            'data' => $books,
            'message' => 'Books retrieved successfully'
        ]);
    }

    /**
     * Store a newly created book in the database.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'is_borrowed' => 'boolean',
        ]);

        $book = $this->bookService->createBook($validated);

        return response()->json([
            'data' => $book,
            'message' => 'Book created successfully'
        ], 201);
    }

    /**
     * Display the specified book.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $book = $this->bookService->findBook($id);

        if (!$book) {
            return response()->json([
                'message' => 'Book not found'
            ], 404);
        }

        return response()->json([
            'data' => $book,
            'message' => 'Book retrieved successfully'
        ]);
    }

    /**
     * Update the specified book in the database.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $book = $this->bookService->findBook($id);

        if (!$book) {
            return response()->json([
                'message' => 'Book not found'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'is_borrowed' => 'boolean',
        ]);

        $updatedBook = $this->bookService->updateBook($id, $validated);

        return response()->json([
            'data' => $updatedBook,
            'message' => 'Book updated successfully'
        ]);
    }

    /**
     * Remove the specified book from the database.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $book = $this->bookService->findBook($id);

        if (!$book) {
            return response()->json([
                'message' => 'Book not found'
            ], 404);
        }

        $this->bookService->deleteBook($id);

        return response()->json([
            'message' => 'Book deleted successfully'
        ]);
    }

    /**
     * Toggle the borrowed status of a specific book.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function toggleBorrowed(int $id): JsonResponse
    {
        $book = $this->bookService->findBook($id);

        if (!$book) {
            return response()->json([
                'message' => 'Book not found'
            ], 404);
        }

        $updatedBook = $this->bookService->toggleBorrowed($id);

        return response()->json([
            'data' => $updatedBook,
            'message' => 'Book borrow status toggled successfully'
        ]);
    }
}
