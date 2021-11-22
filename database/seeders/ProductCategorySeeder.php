<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 1; $i < 20; $i++) {
            Storage::disk('local')->makeDirectory('public/categoryImages/' . $i);
            DB::table('productCategories')->insert([
                'categoryName' => $faker->name,
                'imageFile' => storage_path()
            ]);
        }
    }
}
