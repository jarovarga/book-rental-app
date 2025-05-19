<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Support\Collection;

/**
 * Interface AuthorRepositoryInterface
 *
 * Provides a contract for Author data management, including retrieval,
 * creation, updates, and deletion, as well as additional operations
 * like counting related books.
 */
interface AuthorRepositoryInterface
{
    /**
     * Retrieve a collection of records based on the provided filters.
     *
     * @param array $filters An associative array of filter criteria
     * @return Collection A collection of filtered records
     */
    public function getFiltered(array $filters = []): Collection;

    /**
     * Retrieve all authors from the repository.
     *
     * @return Collection Collection of author objects
     */
    public function all(): Collection;

    /**
     * Find an author by their ID.
     *
     * @param int $id The unique identifier of the author
     * @return object|null The author object if found, null otherwise
     */
    public function find(int $id): ?object;

    /**
     * Create a new author in the repository.
     *
     * @param array $data Author data containing required fields
     * @return object The created author object
     */
    public function create(array $data): object;

    /**
     * Update an existing author's information.
     *
     * @param int $id The unique identifier of the author to update
     * @param array $data Updated author data
     * @return bool True if the update was successful, false otherwise
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete an author from the repository.
     *
     * @param int $id The unique identifier of the author to delete
     * @return bool True if deletion was successful, false otherwise
     */
    public function delete(int $id): bool;

    /**
     * Retrieve all authors with their book count.
     *
     * @return Collection Collection of author objects with additional 'book_count' attribute
     */
    public function withBookCount(): Collection;
}
