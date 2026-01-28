<?php

namespace App\Pipes;

use App\Rules\IsbnRule;
use Closure;
use Illuminate\Support\Facades\Validator;

class ValidateBookRow
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

        $validator = Validator::make($row, [
            'name' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string'],
            'isbn' => ['required', 'unique:books,isbn', new IsbnRule()],
        ]);

        if ($validator->fails()) {
            $data['status'] = 'failed';
            return $data;
        }

        return $next($data);
    }
}
