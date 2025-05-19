<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Author
 *
 * Represents an author in the system.
 *
 * @property int $id The unique identifier of the author
 * @property string $name The first name of the author
 * @property string $surname The last name of the author
 * @property Carbon $created_at Timestamp when the author was created
 * @property Carbon $updated_at Timestamp when the author was last updated
 * @property-read Collection|Book[] $books Collection of books written by this author
 */
class Author extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['name', 'surname'];

    /**
     * Get all books written by this author.
     * Defines a one-to-many relationship between Author and Book models.
     *
     * @return HasMany Books written by this author
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
