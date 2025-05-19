<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Book;
use Illuminate\Support\Collection;

/**
 * Class EloquentBookRepository
 *
 * Eloquent implementation of the BookRepositoryInterface.
 * Handles database operations for Book entities using Laravel's Eloquent ORM.
 * Includes functionality for managing books and their borrowed status.
 */
class EloquentBookRepository implements BookRepositoryInterface
{
    /**
     * Retrieve a filtered collection of books based on the provided filters.
     *
     * @param array $filters An associative array of filters, supporting 'name' and 'surname' as keys
     * @return Collection A collection of books matching the filters, with a count of related books
     */
    public function getFiltered(array $filters = []): Collection
    {
        $query = Book::with('author'); // eager load author relation

        if (!empty($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        if (!empty($filters['author_id'])) {
            $query->where('author_id', $filters['author_id']);
        }

        if (isset($filters['is_borrowed']) && $filters['is_borrowed'] !== '') {
            $query->where('is_borrowed', $filters['is_borrowed']);
        }

        return $query->get();
    }

    /**
     * Retrieve all books from the database with their associated authors.
     * Eagerly loads the author relationship to prevent N+1 query problems.
     *
     * @return Collection<Book> Collection of Book models with loaded author relationships
     */
    public function all(): Collection
    {
        return Book::with('author')->get();
    }

    /**
     * Find a book by its ID.
     *
     * @param int $id The unique identifier of the book
     * @return Book|null The Book model if found, null otherwise
     */
    public function find(int $id): ?Book
    {
        return Book::find($id);
    }

    /**
     * Create a new book in the database.
     *
     * @param array $data Book data containing fillable attributes
     * @return Book The newly created Book model
     */
    public function create(array $data): Book
    {
        return Book::create($data);
    }

    /**
     * Update an existing book's information.
     *
     * @param int $id The unique identifier of the book
     * @param array $data Updated book data
     * @return bool True if the update was successful, false if a book not found or the update failed
     */
    public function update(int $id, array $data): bool
    {
        $book = $this->find($id);

        if (!$book) return false;

        return $book->update($data);
    }

    /**
     * Delete a book from the database.
     *
     * @param int $id The unique identifier of the book
     * @return bool True if deletion was successful, false if a book not found or deletion failed
     */
    public function delete(int $id): bool
    {
        $book = $this->find($id);

        if (!$book) return false;

        return $book->delete();
    }

    /**
     * Toggle the borrowed status of a book.
     * Switches the is_borrowed flag between true and false.
     *
     * @param int $id The unique identifier of the book
     * @return bool True if the status was toggled successfully, false if a book not found or save failed
     */
    public function toggleBorrowed(int $id): bool
    {
        $book = $this->find($id);

        if (!$book) return false;

        $book['is_borrowed'] = !$book['is_borrowed'];

        return $book->save();
    }
}
