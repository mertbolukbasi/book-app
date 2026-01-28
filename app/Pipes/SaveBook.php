<?php

namespace App\Pipes;

use App\Models\Author;
use App\Models\Book;
use Closure;

class SaveBook
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function handle($data, Closure $next)
    {
        $row = $data['row'];

        $author = Author::firstOrCreate([
            'name' => $row['author'],
        ]);

        Book::create([
            'name' => $row['name'],
            'isbn' => $row['isbn'],
            'author_id' => $author->id
        ]);

        $data['status'] = 'success';

        return $next($data);
    }
}
