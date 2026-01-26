<?php

namespace Database\Seeders;

use App\Models\Bookstore;
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
                'email' => 'dr@dr.com',
            ],
            [
                'name' => 'Idefix',
                'email' => 'idefix@idefix.com',
            ],
            [
                'name' => 'Hepsiburada',
                'email' => 'hepsiburada@hepsiburada.com',
            ],
            [
                'name' => 'Kitapyurdu',
                'email' => 'kitapyurdu@kitapyurdu.com',
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
