<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = [
            'Sabahattin Ali',
            'Yaşar Kemal',
            'Lev Tolstoy',
            'George Orwell',
        ];

        foreach ($authors as $name) {
            Author::firstOrCreate(
                ['name' => $name]
            );
        }
    }
}
