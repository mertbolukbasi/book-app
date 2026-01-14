<?php

namespace Database\Seeders;

use App\Models\Bookstore;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookstoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = [
            'D&R',
            'Idefix',
            'Hepsiburada',
            'Kitapyurdu'
        ];

        foreach($stores as $name) {
            Bookstore::firstOrCreate(
                ['name' => $name]
            );
        }
    }
}
