<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bookstore extends Model
{
    public function books(): BelongsToMany // sanirim donus tipi belirtmem gerekiyor ide warn verdi.
    {
        return $this->belongsToMany(Book::class);
    }

    protected $fillable = [
        'name',
    ];
}
