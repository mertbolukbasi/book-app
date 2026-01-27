<?php

namespace App\Pipes;

use App\Models\Author;
use Closure;

class SaveAuthor
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
        Author::create([
            'name' => $row['name'],
        ]);

        $data['status'] = 'success';

        return $next($data);
    }
}
