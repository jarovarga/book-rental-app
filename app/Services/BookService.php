<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\BookRepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Class BookService
 *
 * Service layer for managing book-related business logic.
 * Provides a clean interface between controllers and the book repository,
 * handling operations such as book management and borrowing status.
 */
class BookService
{
    /**
     * @var BookRepositoryInterface The repository handling book data persistence
     */
    protected BookRepositoryInterface $bookRepo;

    /**
     * Initialize the service with its required dependencies.
     *
     * @param BookRepositoryInterface $bookRepo The repository implementation to use
     */
    public function __construct(BookRepositoryInterface $bookRepo)
    {
        $this->bookRepo = $bookRepo;
    }

    /**
     * Retrieve a collection of books based on specified filters.
     *
     * @param array $filters Criteria to filter the books, defaults to an empty array
     * @return Collection A collection of books matching the provided filters
     */
    public function getFilteredBooks(array $filters = []): Collection
    {
        return $this->bookRepo->getFiltered($filters);
    }

    /**
     * Retrieve all books from the system.
     * Returns books with their associated author information.
     *
     * @return Collection Collection of all books with author relationships
     */
    public function getAllBooks(): Collection
    {
        return $this->bookRepo->all();
    }

    /**
     * Find a book by its ID.
     *
     * @param int $id The unique identifier of the book
     * @return object|null The book object if found, null otherwise
     */
    public function findBook(int $id): ?object
    {
        return $this->bookRepo->find($id);
    }

    /**
     * Create a new book with the provided data.
     *
     * @param array $data Book attributes for creation
     * @return object The newly created book object
     */
    public function createBook(array $data): object
    {
        return $this->bookRepo->create($data);
    }

    /**
     * Update an existing book's information.
     *
     * @param int $id The unique identifier of the book to update
     * @param array $data The new book attributes
     * @return bool True if the update was successful, false otherwise
     */
    public function updateBook(int $id, array $data): bool
    {
        return $this->bookRepo->update($id, $data);
    }

    /**
     * Delete a book from the system.
     *
     * @param int $id The unique identifier of the book to delete
     * @return bool True if deletion was successful, false otherwise
     */
    public function deleteBook(int $id): bool
    {
        return $this->bookRepo->delete($id);
    }

    /**
     * Toggle the borrowed status of a book.
     * Changes the book's borrowed status from available to borrow or vice versa.
     *
     * @param int $id The unique identifier of the book
     * @return bool True if the status was successfully toggled, false otherwise
     */
    public function toggleBorrowed(int $id): bool
    {
        return $this->bookRepo->toggleBorrowed($id);
    }
}
