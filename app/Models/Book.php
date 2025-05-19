<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model {
    protected $fillable = ['author_id', 'title', 'is_borrowed'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
