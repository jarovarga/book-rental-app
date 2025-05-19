<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Author;
use Illuminate\Support\Collection;

/**
 * Class EloquentAuthorRepository
 *
 * Eloquent implementation of the AuthorRepositoryInterface.
 * Handles database operations for Author entities using Laravel's Eloquent ORM.
 */
class EloquentAuthorRepository implements AuthorRepositoryInterface
{
    /**
     * Retrieve a collection of authors based on specified filters.
     *
     * @param array $filters An associative array of filters. Supported keys are 'name' and 'surname' for filtering author names and surnames.
     * @return Collection A collection of authors, each including a count of their associated books.
     */
    public function getFiltered(array $filters = []): Collection
    {
        $query = Author::query();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['surname'])) {
            $query->where('surname', 'like', '%' . $filters['surname'] . '%');
        }

        return $query->withCount('books')->get();
    }

    /**
     * Retrieve all authors from the database.
     *
     * @return Collection<Author> Collection of Author models
     */
    public function all(): Collection
    {
        return Author::all();
    }

    /**
     * Find an author by their ID.
     *
     * @param int $id The unique identifier of the author
     * @return Author|null The Author model if found, null otherwise
     */
    public function find(int $id): ?Author
    {
        return Author::find($id);
    }

    /**
     * Create a new author in the database.
     *
     * @param array $data Author data containing fillable attributes
     * @return Author The newly created Author model
     */
    public function create(array $data): Author
    {
        return Author::create($data);
    }

    /**
     * Update an existing author's information.
     *
     * @param int $id The unique identifier of the author
     * @param array $data Updated author data
     * @return bool True if the update was successful, false if author not found, or the update failed
     */
    public function update(int $id, array $data): bool
    {
        $author = $this->find($id);

        if (!$author) return false;

        return $author->update($data);
    }

    /**
     * Delete an author from the database.
     *
     * @param int $id The unique identifier of the author
     * @return bool True if deletion was successful, false if author not found or deletion failed
     */
    public function delete(int $id): bool
    {
        $author = $this->find($id);

        if (!$author) return false;

        return $author->delete();
    }

    /**
     * Retrieve all authors with their book count.
     * Uses Eloquent's withCount method to efficiently count related books.
     *
     * @return Collection<Author> Collection of Author models with additional 'books_count' attribute
     */
    public function withBookCount(): Collection
    {
        return Author::withCount('books')->get();
    }
}
