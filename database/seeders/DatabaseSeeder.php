<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([UsersTableSeeder::class]);
        $this->call([ProductCategorySeeder::class]);
        $this->call([ProductSeeder::class]);
        $this->call([CitySeeder::class]);
        $this->call([CustomerSeeder::class]);
        $this->call([InvoiceSeeder::class]);
        $this->call([InvoiceLineSeeder::class]);
    }
}
