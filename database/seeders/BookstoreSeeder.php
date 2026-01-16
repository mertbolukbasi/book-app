<?php

namespace Database\Seeders;

use App\Models\Bookstore;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// php artisan db:seed --class=BookstoreSeeder
class BookstoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = [
            [
                'name' => 'D&R',
                'email' => 'bolukbasmert@hotmail.com',
            ],
            [
                'name' => 'Idefix',
                'email' => 'bolukbasmertr2004@hotmail.com',
            ],
            [
                'name' => 'Hepsiburada',
                'email' => 'mertbolukbasi144@hotmail.com',
            ],
            [
                'name' => 'Kitapyurdu',
                'email' => 'kaizenium@kaizeniumfoundation.com',
            ],
        ];

        foreach ($stores as $store) {
            Bookstore::firstOrCreate(
                ['name' => $store['name']],
                ['email' => $store['email']]
            );
        }
    }
}
