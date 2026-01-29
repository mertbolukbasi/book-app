<?php

namespace App\Pipes;

use App\Models\Author;
use Closure;

class CheckDuplicate
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
        $author_exists = Author::where('name', $row['name'])->exists();
        if ($author_exists) {
            $data['status'] = 'failed';
            return $data;
        }

        return $next($data);
    }
}
