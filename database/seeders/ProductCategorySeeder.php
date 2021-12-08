<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use JeroenZwart\CsvSeeder\CsvSeeder;

class ProductCategorySeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->file = '/database/seeds/csvs/productCategories.csv';
        $this->timestamps = false;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Recommended when importing larger CSVs
        DB::disableQueryLog();
        parent::run();
    }
}
