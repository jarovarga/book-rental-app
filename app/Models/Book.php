<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Book
 *
 * Represents a book in the system.
 *
 * @property int $id The unique identifier of the book
 * @property int $author_id Foreign key referencing the author
 * @property string $title The title of the book
 * @property bool $is_borrowed Flag indicating if the book is currently borrowed
 * @property Carbon $created_at Timestamp when the book was created
 * @property Carbon $updated_at Timestamp when the book was last updated
 * @property-read Author $author The author who wrote this book
 */
class Book extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['author_id', 'title', 'is_borrowed'];

    /**
     * Get the author of this book.
     * Defines a belongs-to relationship between Book and Author models.
     *
     * @return BelongsTo The author of this book
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
