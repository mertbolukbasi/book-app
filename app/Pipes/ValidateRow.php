<?php

namespace App\Pipes;

use Closure;

class ValidateRow
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
        if (empty($row['name']) || strlen($row['name']) > 255) {
            $data['status'] = 'failed';
            return $data;
        }

        return $next($data);
    }
}
