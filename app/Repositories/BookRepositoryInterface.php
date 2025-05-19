<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Support\Collection;

/**
 * Interface BookRepositoryInterface
 *
 * Defines the contract for managing book entities in the system.
 * This interface provides methods for basic CRUD operations and book-specific functionality.
 */
interface BookRepositoryInterface
{
    /**
     * Retrieve a collection of records based on the provided filters.
     *
     * @param array $filters An associative array of filter criteria
     * @return Collection A collection of filtered records
     */
    public function getFiltered(array $filters = []): Collection;

    /**
     * Retrieve all books from the repository.
     *
     * @return Collection Collection of book objects
     */
    public function all(): Collection;

    /**
     * Find a book by its ID.
     *
     * @param int $id The unique identifier of the book
     * @return object|null The book object if found, null otherwise
     */
    public function find(int $id): ?object;

    /**
     * Create a new book in the repository.
     *
     * @param array $data Book data containing required fields
     * @return object The created book object
     */
    public function create(array $data): object;

    /**
     * Update an existing book's information.
     *
     * @param int $id The unique identifier of the book to update
     * @param array $data Updated book data
     * @return bool True if the update was successful, false otherwise
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a book from the repository.
     *
     * @param int $id The unique identifier of the book to delete
     * @return bool True if deletion was successful, false otherwise
     */
    public function delete(int $id): bool;

    /**
     * Toggle the borrowed status of a book.
     *
     * @param int $id The unique identifier of the book
     * @return bool True if the status was toggled successfully, false otherwise
     */
    public function toggleBorrowed(int $id): bool;
}
