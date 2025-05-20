<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthorService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthorController extends Controller
{
    /**
     * @var AuthorService Service layer handling author-related operations
     */
    protected AuthorService $authorService;

    /**
     * Initialize a controller with its required dependencies.
     *
     * @param AuthorService $authorService Service for author operations
     */
    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    /**
     * Display a listing of authors.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $authors = $this->authorService->getAllAuthorsWithBookCount();

        return response()->json([
            'data' => $authors,
            'message' => 'Authors retrieved successfully'
        ]);
    }

    /**
     * Store a newly created author in the database.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
        ]);

        $author = $this->authorService->createAuthor($validated);

        return response()->json([
            'data' => $author,
            'message' => 'Author created successfully'
        ], 201);
    }

    /**
     * Display the specified author.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $author = $this->authorService->findAuthor($id);

        if (!$author) {
            return response()->json([
                'message' => 'Author not found'
            ], 404);
        }

        return response()->json([
            'data' => $author,
            'message' => 'Author retrieved successfully'
        ]);
    }

    /**
     * Update the specified author in the database.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $author = $this->authorService->findAuthor($id);

        if (!$author) {
            return response()->json([
                'message' => 'Author not found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
        ]);

        $updatedAuthor = $this->authorService->updateAuthor($id, $validated);

        return response()->json([
            'data' => $updatedAuthor,
            'message' => 'Author updated successfully'
        ]);
    }

    /**
     * Remove the specified author from the database.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $author = $this->authorService->findAuthor($id);

        if (!$author) {
            return response()->json([
                'message' => 'Author not found'
            ], 404);
        }

        try {
            $this->authorService->deleteAuthor($id);

            return response()->json([
                'message' => 'Author deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Author not found'
            ], 404);
        } catch (\Exception $e) {
            // Check if the error is related to a foreign key constraint
            if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
                return response()->json([
                    'message' => 'Cannot delete author with associated books'
                ], 409);
            }

            return response()->json([
                'message' => 'An error occurred while deleting the author'
            ], 500);
        }
    }
}
