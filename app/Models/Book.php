<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function bookstores(): BelongsToMany
    {
        return $this->belongsToMany(Bookstore::class);
    }


    protected $fillable = [
        'book_name',
        'image',
        'isbn',
        'author_id',
    ];
}
