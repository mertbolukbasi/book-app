<?php

namespace App\Pipes;

use App\Models\Author;
use App\Models\Book;
use App\Models\Bookstore;
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

        $book = Book::create([
            'name' => $row['name'],
            'isbn' => $row['isbn'],
            'author_id' => $author->id,
        ]);

        $storeNames = array_filter(array_map('trim', explode(',', $row['stores'])));
        $storeIds = Bookstore::whereIn('name', $storeNames)->pluck('id');
        $book->bookstores()->attach($storeIds);

        $data['status'] = 'success';

        return $next($data);
    }
}
