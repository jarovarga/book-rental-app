<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AuthorRepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Class AuthorService
 *
 * Service layer for managing author-related business logic.
 * Acts as an intermediary between controllers and the author repository,
 * providing a clean API for author management operations.
 */
class AuthorService
{
    /**
     * @var AuthorRepositoryInterface The repository handling author data persistence
     */
    protected AuthorRepositoryInterface $authorRepo;

    /**
     * Initialize the service with its required dependencies.
     *
     * @param AuthorRepositoryInterface $authorRepo The repository implementation to use
     */
    public function __construct(AuthorRepositoryInterface $authorRepo)
    {
        $this->authorRepo = $authorRepo;
    }

    /**
     * Retrieve a collection of authors based on the provided filters.
     *
     * @param array $filters Optional filters to apply when retrieving authors
     * @return Collection A collection of authors matching the specified filters
     */
    public function getFilteredAuthors(array $filters = []): Collection
    {
        return $this->authorRepo->getFiltered($filters);
    }

    /**
     * Retrieve all authors with their associated book counts.
     *
     * @return Collection Collection of authors with 'books_count' attribute
     */
    public function getAllAuthorsWithBookCount(): Collection
    {
        return $this->authorRepo->withBookCount();
    }

    /**
     * Retrieve all authors from the system.
     *
     * @return Collection Collection of all authors
     */
    public function getAllAuthors(): Collection
    {
        return $this->authorRepo->all();
    }

    /**
     * Find an author by their ID.
     *
     * @param int $id The unique identifier of the author
     * @return object|null The author object if found, null otherwise
     */
    public function findAuthor(int $id): ?object
    {
        return $this->authorRepo->find($id);
    }

    /**
     * Create a new author with the provided data.
     *
     * @param array $data Author attributes for creation
     * @return object The newly created author object
     */
    public function createAuthor(array $data): object
    {
        return $this->authorRepo->create($data);
    }

    /**
     * Update an existing author's information.
     *
     * @param int $id The unique identifier of the author to update
     * @param array $data The new author attributes
     * @return bool True if the update was successful, false otherwise
     */
    public function updateAuthor(int $id, array $data): bool
    {
        return $this->authorRepo->update($id, $data);
    }

    /**
     * Delete an author from the system.
     *
     * @param int $id The unique identifier of the author to delete
     * @return bool True if deletion was successful, false otherwise
     */
    public function deleteAuthor(int $id): bool
    {
        return $this->authorRepo->delete($id);
    }
}
